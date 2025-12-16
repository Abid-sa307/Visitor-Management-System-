<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px 0; border-top: 1px solid #e5e7eb; margin-top: 30px;">
    <div style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <!-- Header / Brand Section -->
        <div style="background-color: #1e293b; color: white; padding: 20px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 120px; height: 120px; background-color: #2563eb; border-radius: 50%; mix-blend-mode: multiply; filter: blur(30px); transform: translate(50%, -50%);"></div>
            <div style="position: relative; z-index: 10; display: flex; align-items: center; gap: 15px;">
                <div style="width: 48px; height: 48px; background-color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); padding: 4px;">
                    <img src="{{ $message->embed(public_path('images/mail.jpeg')) }}" alt="N&T Software Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 20px; font-weight: 700; line-height: 1.2;">N & T Software Pvt. Ltd.</h1>
                    <p style="color: #93c5fd; margin: 4px 0 0; font-size: 11px; font-weight: 500; text-transform: uppercase;">
                        We Build Digital Products For Next-Gen Businesses
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px; padding: 20px; border-bottom: 1px solid #f1f5f9;">
            <!-- Contact & Location -->
            <div>
                <h2 style="font-size: 16px; color: #1e293b; margin: 0 0 10px 0; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                    Contact & Location
                </h2>
                <div style="display: grid; gap: 12px;">
                    <!-- Mobile -->
                    <div style="display: flex; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                                <line x1="12" y1="18" x2="12" y2="18"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size: 11px; color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase;">Mobile</p>
                            <p style="font-size: 13px; color: #1e293b; font-weight: 500; margin: 2px 0 0 0;">+91 84870 80659</p>
                        </div>
                    </div>

                    <!-- General Email -->
                    <div style="display: flex; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #fef2f2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <img src="{{ $message->embed(public_path('images/mail.jpeg')) }}" alt="Email" style="width: 16px; height: 16px; object-fit: contain;">
                        </div>
                        <div>
                            <p style="font-size: 11px; color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase;">General Inquiry</p>
                            <p style="font-size: 12px; color: #1e293b; font-weight: 500; margin: 2px 0 0 0; word-break: break-all;">visitormanagmentsystemsoftware@gmail.com</p>
                        </div>
                    </div>
                    
                    <!-- Sales Email -->
                    <div style="display: flex; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #fef2f2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <img src="{{ $message->embed(public_path('images/mail.jpeg')) }}" alt="Email" style="width: 16px; height: 16px; object-fit: contain;">
                        </div>
                        <div>
                            <p style="font-size: 11px; color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase;">Sales Inquiry</p>
                            <p style="font-size: 13px; color: #1e293b; font-weight: 500; margin: 2px 0 0 0;">sales@nntsoftware.com</p>
                        </div>
                    </div>

                    <!-- Website -->
                    <div style="display: flex; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #eef2ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                                <line x1="2.1" y1="12" x2="21.9" y2="12"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size: 11px; color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase;">Website</p>
                            <p style="font-size: 13px; color: #1e293b; font-weight: 500; margin: 2px 0 0 0;">www.nntsoftware.com</p>
                        </div>
                    </div>

                    <!-- Address -->
                    <div style="display: flex; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #f8fafc; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size: 11px; color: #64748b; font-weight: 600; margin: 0; text-transform: uppercase;">Office</p>
                            <p style="font-size: 13px; color: #1e293b; font-weight: 500; margin: 2px 0 0 0; line-height: 1.3;">3rd Floor, Diamond Complex, Chhapi, North Gujarat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Codes & Services -->
        <div style="padding: 20px; display: grid; gap: 20px;">
            <!-- QR Codes -->
            <div style="display: flex; gap: 15px;">
                <!-- Website QR -->
                <div style="flex: 1; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https%3A%2F%2Fwww.nntsoftware.com&color=2563eb" alt="Website QR" style="width: 80px; height: 80px; margin-bottom: 8px;"/>
                    <p style="font-size: 11px; font-weight: 600; color: #1e293b; margin: 0;">Scan Website</p>
                </div>

                <!-- WhatsApp QR -->
                <div style="flex: 1; padding: 12px; border: 1px solid #dcfce7; border-radius: 8px; background-color: #f0fdf4; display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https%3A%2F%2Fwa.me%2F918487080659&color=16a34a" alt="WhatsApp QR" style="width: 80px; height: 80px; margin-bottom: 8px;"/>
                    <p style="font-size: 11px; font-weight: 600; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L2 22l1.3-3.9A9 9 0 0 1 2 12 8.38 8.38 0 0 1 2.9 8.2 8.5 8.5 0 0 1 7.5 3.5" stroke-linecap="round"/>
                            <polyline points="12 18 12 12 18 12" stroke-linecap="round"/>
                        </svg>
                        WhatsApp Chat
                    </p>
                </div>
            </div>

            <!-- Services List -->
            <div style="background-color: #eff6ff; padding: 16px; border-radius: 8px; border: 1px solid #dbeafe;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #1e40af;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                        <line x1="6" y1="6" x2="6" y2="6"/>
                        <line x1="6" y1="18" x2="6" y2="18"/>
                    </svg>
                    <h3 style="font-size: 13px; font-weight: 700; margin: 0;">Solutions Provided</h3>
                </div>
                <ul style="margin: 0; padding: 0; list-style: none; display: grid; gap: 6px;">
                    <li style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Bulk Hardware Supply
                    </li>
                    <li style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Custom Software Development
                    </li>
                    <li style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Data Center Services
                    </li>
                    <li style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Security & Firewall Management
                    </li>
                    <li style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #1e293b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Database Management & Hosting
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px; text-align: center; font-size: 11px; color: #64748b;">
            Yours sincerely, N&T Software TEAM • All Rights Reserved © {{ date('Y') }}
        </div>
    </div>
</div>
