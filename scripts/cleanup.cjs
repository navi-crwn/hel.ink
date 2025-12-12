const fs = require('fs');
const path = require('path');

// Configuration
const TARGET_DIR = path.resolve(__dirname, '..'); // Root directory (assuming script is in /scripts)
const EXTENSIONS = ['.js', '.ts', '.jsx', '.tsx', '.php', '.blade.php'];
const EXCLUDE_DIRS = ['node_modules', 'vendor', '.git', 'storage', 'public/build', 'dist', 'coverage'];

// Regex Patterns
const PATTERNS = [
    // 1. Debug Cleanup: console.log, info, debug (Keep error, warn)
    {
        regex: /^\s*console\.(log|info|debug)\(.*\);?\s*$/gm,
        replacement: ''
    },
    // 2. Debug Cleanup: debugger;
    {
        regex: /^\s*debugger;?\s*$/gm,
        replacement: ''
    },
    // 3. Smart Comment Removal: Decorative (===), TODO, FIXME
    {
        regex: /^\s*\/\/.*(?:={3,}|TODO:|FIXME:|LOGIKA).*$/gm,
        replacement: ''
    },
    // 4. Commented out code (Heuristic: starts with // followed by const, let, var, function, class, public, private)
    {
        regex: /^\s*\/\/\s*(?:const|let|var|function|class|public|private|protected|import|export)\s+.*$/gm,
        replacement: ''
    }
];

// Statistics
let stats = {
    filesProcessed: 0,
    linesRemoved: 0
};

function walk(dir) {
    const files = fs.readdirSync(dir);

    files.forEach(file => {
        const filePath = path.join(dir, file);
        const stat = fs.statSync(filePath);

        if (stat.isDirectory()) {
            if (!EXCLUDE_DIRS.includes(file)) {
                walk(filePath);
            }
        } else {
            if (EXTENSIONS.includes(path.extname(file))) {
                processFile(filePath);
            }
        }
    });
}

function processFile(filePath) {
    let content = fs.readFileSync(filePath, 'utf8');
    let originalContent = content;

    PATTERNS.forEach(pattern => {
        content = content.replace(pattern.regex, (match) => {
            stats.linesRemoved++;
            return pattern.replacement;
        });
    });

    // Remove multiple empty lines resulting from deletion
    content = content.replace(/^\s*[\r\n]/gm, '');

    if (content !== originalContent) {
        fs.writeFileSync(filePath, content, 'utf8');
        console.log(`[CLEANED] ${path.relative(TARGET_DIR, filePath)}`);
        stats.filesProcessed++;
    }
}

console.log('🚀 Starting Codebase Hygiene Scan...');
console.log(`Target: ${TARGET_DIR}`);
walk(TARGET_DIR);
console.log('✨ Scan Complete!');
console.log(`Files Cleaned: ${stats.filesProcessed}`);
console.log(`Lines Removed: ${stats.linesRemoved}`);
