<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'light' }" x-bind:class="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms of Service - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center space-x-3">
                            <img x-show="theme === 'dark'" src="{{ route('brand.logo') }}" alt="Logo" class="h-10">
                            <img x-show="theme === 'light'" src="{{ route('brand.logo.dark') }}" alt="Logo" class="h-10">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">HOP EASY LINK</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="theme = (theme === 'dark' ? 'light' : 'dark'); localStorage.setItem('theme', theme)" 
                                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Login</a>
                        <a href="{{ route('register') }}" class="bg-slate-800 dark:bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 dark:hover:bg-blue-700">Register</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <h1 class="text-4xl font-bold mb-2">Terms of Service</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">Last Updated: November 22, 2025</p>

                <div class="space-y-8 text-gray-700 dark:text-gray-300">
                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">1. Acceptance of Terms</h2>
                        <p class="mb-3">By accessing or using {{ config('app.name') }} ("Service"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of the terms, you may not access the Service.</p>
                        <p>The Service is intended for users who are at least 13 years old. By using the Service, you represent and warrant that you are at least 13 years of age.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">2. Description of Service</h2>
                        <p class="mb-3">{{ config('app.name') }} is a URL shortening and link management service that allows users to:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Create shortened URLs from long web addresses</li>
                            <li>Track click analytics and statistics</li>
                            <li>Organize links with folders and tags</li>
                            <li>Generate QR codes for shortened links</li>
                            <li>Set expiration dates and password protection (registered users only)</li>
                            <li>Customize short URLs with custom slugs (subject to availability)</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">3. User Accounts and Registration</h2>
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">3.1 Account Creation</h3>
                        <p class="mb-3">To access certain features, you must create an account by providing accurate, current, and complete information. You are responsible for maintaining the confidentiality of your account credentials.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">3.2 Account Security</h3>
                        <p class="mb-3">You are responsible for all activities that occur under your account. You must immediately notify us of any unauthorized use of your account or any other breach of security.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">3.3 Account Termination</h3>
                        <p>We reserve the right to suspend or terminate your account at any time for violations of these Terms or for any other reason we deem appropriate.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">4. Acceptable Use Policy</h2>
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">4.1 Permitted Use</h3>
                        <p class="mb-3">You may use the Service for lawful purposes only and in accordance with these Terms.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">4.2 Prohibited Activities</h3>
                        <p class="mb-3">You agree NOT to use the Service to create, share, or distribute shortened URLs that:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4 mb-3">
                            <li><strong>Illegal Content:</strong> Violate any local, state, national, or international law or regulation</li>
                            <li><strong>Malware & Phishing:</strong> Contain viruses, malware, spyware, or any malicious code; conduct phishing attacks or identity theft</li>
                            <li><strong>Spam & Abuse:</strong> Send unsolicited bulk emails (spam) or abuse, harass, threaten, or intimidate others</li>
                            <li><strong>Intellectual Property:</strong> Infringe upon patents, trademarks, copyrights, or other intellectual property rights</li>
                            <li><strong>Adult Content:</strong> Link to pornographic, sexually explicit, or adult content without proper age verification</li>
                            <li><strong>Hate Speech:</strong> Promote hate speech, discrimination, or violence based on race, religion, gender, sexual orientation, disability, or nationality</li>
                            <li><strong>Terrorism:</strong> Support or promote terrorist organizations or activities</li>
                            <li><strong>Fraud & Scams:</strong> Engage in fraudulent activities, pyramid schemes, or other deceptive practices</li>
                            <li><strong>Child Safety:</strong> Exploit, harm, or attempt to exploit minors in any way</li>
                            <li><strong>Drugs & Weapons:</strong> Sell or facilitate the sale of illegal drugs, weapons, or prohibited items</li>
                            <li><strong>Privacy Violation:</strong> Violate the privacy rights of others or collect personal data without consent</li>
                            <li><strong>Service Abuse:</strong> Attempt to gain unauthorized access to our systems or interfere with the Service</li>
                            <li><strong>URL Manipulation:</strong> Use misleading short URLs that disguise the actual destination</li>
                        </ul>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">4.3 Rate Limits and Quotas</h3>
                        <p class="mb-3">We impose reasonable rate limits and usage quotas to ensure fair use of the Service. Exceeding these limits may result in temporary or permanent suspension of your account.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">5. Content and Intellectual Property</h2>
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">5.1 Your Content</h3>
                        <p class="mb-3">You retain ownership of all content you submit through the Service. By creating shortened URLs, you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, and display your shortened URLs for the purpose of providing the Service.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">5.2 Our Content</h3>
                        <p class="mb-3">The Service, including its original content, features, and functionality, is owned by {{ config('app.name') }} and is protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">5.3 DMCA Compliance</h3>
                        <p>We respect the intellectual property rights of others. If you believe that material available on the Service infringes your copyright, you may submit a DMCA takedown notice to our designated copyright agent.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">6. Privacy and Data Protection</h2>
                        <p class="mb-3">Your privacy is important to us. Our Privacy Policy explains how we collect, use, and protect your personal information. By using the Service, you consent to our collection and use of information as described in our Privacy Policy.</p>
                        <p class="mb-3"><strong>Data We Collect:</strong></p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Account information (name, email, password hash)</li>
                            <li>Click analytics (IP addresses, geographic location, referrer, user agent)</li>
                            <li>Usage patterns and preferences</li>
                            <li>Cookies and similar tracking technologies</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">7. Link Monitoring and Enforcement</h2>
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">7.1 Automated Monitoring</h3>
                        <p class="mb-3">We employ automated systems to detect and prevent abuse of the Service. Shortened URLs may be scanned for malicious content, malware, and compliance with these Terms.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">7.2 Abuse Reporting</h3>
                        <p class="mb-3">Users can report abusive or violating content. We will investigate all reports and take appropriate action, which may include disabling the shortened URL or suspending the user's account.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">7.3 Link Disabling</h3>
                        <p>We reserve the right to disable any shortened URL that violates these Terms without prior notice. Disabled links will redirect to an error page.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">8. Disclaimers and Limitations of Liability</h2>
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">8.1 Service Availability</h3>
                        <p class="mb-3">THE SERVICE IS PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS. WE DO NOT WARRANT THAT THE SERVICE WILL BE UNINTERRUPTED, SECURE, OR ERROR-FREE.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">8.2 Third-Party Websites</h3>
                        <p class="mb-3">The Service allows you to create links to third-party websites. We are not responsible for the content, accuracy, or availability of such websites. Links to third-party sites do not imply endorsement.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">8.3 Limitation of Liability</h3>
                        <p class="mb-3">TO THE MAXIMUM EXTENT PERMITTED BY LAW, {{ config('app.name') }} SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING BUT NOT LIMITED TO:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Loss of profits, data, or business opportunities</li>
                            <li>Service interruptions or security breaches</li>
                            <li>Errors, mistakes, or inaccuracies in the Service</li>
                            <li>Personal injury or property damage resulting from your use of the Service</li>
                            <li>Unauthorized access to your account or data</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">9. Indemnification</h2>
                        <p>You agree to indemnify, defend, and hold harmless {{ config('app.name') }}, its officers, directors, employees, and agents from and against any claims, liabilities, damages, losses, and expenses arising out of or in any way connected with:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                            <li>Your access to or use of the Service</li>
                            <li>Your violation of these Terms</li>
                            <li>Your violation of any third-party rights, including intellectual property rights</li>
                            <li>Any content you submit or distribute through the Service</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">10. Data Retention and Deletion</h2>
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">10.1 Link Expiration</h3>
                        <p class="mb-3">Registered users may set expiration dates for their shortened URLs. Expired links will automatically become inactive and redirect to an error page.</p>
                        
                        <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-gray-200">10.2 Account Deletion</h3>
                        <p class="mb-3">You may delete your account at any time from your profile settings. Upon deletion:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>All your shortened URLs will be permanently deleted</li>
                            <li>All analytics data associated with your account will be removed</li>
                            <li>Your personal information will be deleted within 30 days</li>
                            <li>Some aggregated, anonymized data may be retained for statistical purposes</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">11. Changes to Terms</h2>
                        <p class="mb-3">We reserve the right to modify these Terms at any time. If we make material changes, we will notify you by email or through a prominent notice on the Service.</p>
                        <p>Your continued use of the Service after any changes to these Terms constitutes acceptance of those changes.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">12. Governing Law and Dispute Resolution</h2>
                        <p class="mb-3">These Terms shall be governed by and construed in accordance with the laws of [Your Country/State], without regard to its conflict of law provisions.</p>
                        <p>Any disputes arising out of or relating to these Terms or the Service shall be resolved through binding arbitration in accordance with the rules of [Arbitration Body], except that either party may seek injunctive or other equitable relief in any court of competent jurisdiction.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">13. Contact Information</h2>
                        <p class="mb-3">If you have any questions about these Terms, please contact us:</p>
                        <ul class="list-none space-y-2 ml-4">
                            <li><strong>Email:</strong> legal@{{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'helink.test' }}</li>
                            <li><strong>Website:</strong> {{ config('app.url') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">14. Severability</h2>
                        <p>If any provision of these Terms is found to be unenforceable or invalid, that provision will be limited or eliminated to the minimum extent necessary so that the Terms will otherwise remain in full force and effect.</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">15. Entire Agreement</h2>
                        <p>These Terms constitute the entire agreement between you and {{ config('app.name') }} regarding the use of the Service and supersede all prior agreements and understandings.</p>
                    </section>

                    <div class="mt-12 p-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <h3 class="text-xl font-semibold mb-3 text-yellow-900 dark:text-yellow-200">⚠️ Important Warnings</h3>
                        <ul class="list-disc list-inside space-y-2 text-yellow-800 dark:text-yellow-300">
                            <li>Do not use this service for any illegal activities</li>
                            <li>We cooperate with law enforcement and may disclose user data when required by law</li>
                            <li>Accounts found in violation of these Terms will be terminated without refund</li>
                            <li>We reserve the right to modify or discontinue the Service at any time</li>
                            <li>Shortened URLs are public and can be accessed by anyone with the link</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            ← Back to Registration
                        </a>
                        <a href="{{ url('/') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-600 dark:text-gray-400 text-sm">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
