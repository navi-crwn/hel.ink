@props(['link'])
<div x-data="advancedQrGenerator()" class="space-y-6">
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 border border-slate-200 dark:border-slate-700">
        <div class="flex flex-col items-center">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">QR Code Preview</h3>
            <div class="relative" :style="`background: ${settings.frameColor}; padding: 40px; border-radius: 24px;`">
                <div id="qr-canvas-container" class="bg-white p-4 rounded-xl"></div>
                <div class="mt-4 text-center">
                    <input 
                        type="text" 
                        x-model="settings.frameText"
                        maxlength="15"
                        placeholder="SCAN ME"
                        class="w-full text-center text-2xl font-bold bg-transparent border-none focus:outline-none"
                        :style="`color: ${settings.frameTextColor};`"
                    >
                    <p class="text-xs mt-1 opacity-75" :style="`color: ${settings.frameTextColor};`">
                        <span x-text="settings.frameText.length"></span>/15 characters
                    </p>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button @click="downloadQR('png')" class="px-6 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-700 transition-colors font-semibold">
                    Download PNG
                </button>
                <button @click="downloadQR('svg')" class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-500 transition-colors font-semibold">
                    Download SVG
                </button>
                <button @click="saveToServer()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition-colors font-semibold">
                    Save to Server
                </button>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Customize QR Code</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Module Style</label>
                <select x-model="settings.dotsType" @change="updateQR()" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    <option value="square">Square</option>
                    <option value="dots">Dots</option>
                    <option value="rounded">Rounded</option>
                    <option value="extra-rounded">Extra Rounded</option>
                    <option value="classy">Classy</option>
                    <option value="classy-rounded">Classy Rounded</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Corner Square (Outer)</label>
                <select x-model="settings.cornerSquareType" @change="updateQR()" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    <option value="square">Square</option>
                    <option value="dot">Dot</option>
                    <option value="extra-rounded">Extra Rounded</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Corner Dot (Inner)</label>
                <select x-model="settings.cornerDotType" @change="updateQR()" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    <option value="square">Square</option>
                    <option value="dot">Dot</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">QR Color</label>
                <div class="flex gap-2">
                    <input type="color" x-model="settings.dotsColor" @change="updateQR()" class="h-10 w-20 rounded border border-slate-300 cursor-pointer">
                    <input type="text" x-model="settings.dotsColor" @change="updateQR()" class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Background Color</label>
                <div class="flex gap-2">
                    <input type="color" x-model="settings.backgroundColor" @change="updateQR()" class="h-10 w-20 rounded border border-slate-300 cursor-pointer">
                    <input type="text" x-model="settings.backgroundColor" @change="updateQR()" class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Corner Color</label>
                <div class="flex gap-2">
                    <input type="color" x-model="settings.cornerSquareColor" @change="updateQR()" class="h-10 w-20 rounded border border-slate-300 cursor-pointer">
                    <input type="text" x-model="settings.cornerSquareColor" @change="updateQR()" class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Frame Color</label>
                <div class="flex gap-2">
                    <input type="color" x-model="settings.frameColor" class="h-10 w-20 rounded border border-slate-300 cursor-pointer">
                    <input type="text" x-model="settings.frameColor" class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Frame Text Color</label>
                <div class="flex gap-2">
                    <input type="color" x-model="settings.frameTextColor" class="h-10 w-20 rounded border border-slate-300 cursor-pointer">
                    <input type="text" x-model="settings.frameTextColor" class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Logo (Center)</label>
                <input 
                    type="file" 
                    accept="image/*"
                    @change="handleLogoUpload($event)"
                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-500"
                >
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    💡 Logo will create a clear white space in center. Use high error correction (Level H).
                </p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.min.js"></script>
<script>
function advancedQrGenerator() {
    return {
        qrCode: null,
        settings: {
            width: 512,
            height: 512,
            data: '{{ $link->short_url }}',
            margin: 5,
            dotsType: 'rounded',
            dotsColor: '#000000',
            backgroundColor: '#ffffff',
            cornerSquareType: 'extra-rounded',
            cornerSquareColor: '#000000',
            cornerDotType: 'dot',
            cornerDotColor: '#000000',
            frameColor: '#1e40af',
            frameText: 'SCAN ME',
            frameTextColor: '#ffffff',
            logoImage: null,
            logoWidth: 100,
            logoHeight: 100,
            logoMargin: 10, // Clear zone around logo
        },
        init() {
            this.initQR();
        },
        initQR() {
            const options = {
                width: this.settings.width,
                height: this.settings.height,
                type: 'canvas',
                data: this.settings.data,
                margin: this.settings.margin,
                qrOptions: {
                    typeNumber: 0,
                    mode: 'Byte',
                    errorCorrectionLevel: 'H' // HIGH for logo support
                },
                imageOptions: {
                    hideBackgroundDots: true, // CRUCIAL: Clear dots behind logo
                    imageSize: 0.35, // Logo size 35% of QR (larger and more visible)
                    margin: 5, // Clear zone
                    crossOrigin: 'anonymous',
                },
                dotsOptions: {
                    color: this.settings.dotsColor,
                    type: this.settings.dotsType
                },
                backgroundOptions: {
                    color: this.settings.backgroundColor,
                },
                cornersSquareOptions: {
                    color: this.settings.cornerSquareColor,
                    type: this.settings.cornerSquareType,
                },
                cornersDotOptions: {
                    color: this.settings.cornerDotColor,
                    type: this.settings.cornerDotType,
                }
            };
            if (this.settings.logoImage) {
                options.image = this.settings.logoImage;
            }
            this.qrCode = new QRCodeStyling(options);
            const container = document.getElementById('qr-canvas-container');
            container.innerHTML = '';
            this.qrCode.append(container);
        },
        updateQR() {
            if (!this.qrCode) return;
            this.qrCode.update({
                data: this.settings.data,
                dotsOptions: {
                    color: this.settings.dotsColor,
                    type: this.settings.dotsType
                },
                backgroundOptions: {
                    color: this.settings.backgroundColor,
                },
                cornersSquareOptions: {
                    color: this.settings.cornerSquareColor,
                    type: this.settings.cornerSquareType,
                },
                cornersDotOptions: {
                    color: this.settings.cornerDotColor,
                    type: this.settings.cornerDotType,
                },
                imageOptions: {
                    hideBackgroundDots: true,
                    imageSize: 0.35,
                    margin: 5,
                }
            });
        },
        handleLogoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.settings.logoImage = e.target.result;
                this.initQR(); // Reinit with logo
            };
            reader.readAsDataURL(file);
        },
        async downloadQR(format) {
            if (!this.qrCode) return;
            const extension = format === 'svg' ? 'svg' : 'png';
            await this.qrCode.download({
                name: '{{ $link->slug }}-qr',
                extension: extension
            });
        },
        async saveToServer() {
            if (!this.qrCode) {
                alert('QR Code not generated');
                return;
            }
            try {
                // Get canvas as blob
                const blob = await this.qrCode.getRawData('png');
                // Convert to base64
                const reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = async () => {
                    const base64data = reader.result;
                    // Send to server
                    const response = await fetch('{{ route("links.qr.save", $link) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            qr_image: base64data,
                            settings: this.settings
                        })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert('✓ QR Code saved successfully!');
                        // Trigger reload to show updated QR in main modal
                        window.location.reload();
                    } else {
                        alert('Failed to save: ' + (result.error || 'Unknown error'));
                    }
                };
            } catch (error) {
                console.error('Error saving QR:', error);
                alert('Error saving QR code');
            }
        }
    }
}
</script>
