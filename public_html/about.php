<?php
/**
 * About Page
 * Information about the site, technology, and licensing
 * 
 * Place this file in: /about.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Set page title and active tab
$page_title = 'About This Site';
$active_tab = '';

// Include header
include 'includes/frontend_header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <header class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">About This Site</h1>
                <div class="divider-custom">
                    <div class="divider-line"></div>
                    <div class="divider-icon"><i class="ti ti-file-certificate"></i></div>
                    <div class="divider-line"></div>
                </div>
                <p class="lead text-muted">The technology and people behind the Northern Colorado EMS Protocols</p>
            </header>
            
            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="ti ti-heart-rate-monitor"></i>
                        </div>
                        <h3>Purpose</h3>
                        <p>Providing emergency responders with instant access to life-saving protocols</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="ti ti-device-mobile"></i>
                        </div>
                        <h3>Access</h3>
                        <p>Mobile-friendly design works on any device, anywhere, anytime</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="ti ti-license"></i>
                        </div>
                        <h3>Open Source</h3>
                        <p>Free to use and modify under Creative Commons licensing</p>
                    </div>
                </div>
            </div>

            <section class="about-section mb-5">
                <div class="section-header d-flex align-items-center">
                    <i class="ti ti-ambulance me-3"></i>
                    <h2>Northern Colorado EMS Protocols</h2>
                </div>
                <div class="card about-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="lead">Welcome to the digital home of the Northern Colorado EMS Protocols. This site was created to provide emergency medical providers with quick and easy access to the protocols that guide their clinical practice.</p>
                                <p>These protocols are maintained and updated regularly to ensure they reflect current best practices in emergency medical care. With this digital format, we're able to provide an experience that's:</p>
                                <ul class="feature-list">
                                    <li><i class="ti ti-search"></i> <span>Searchable - find what you need quickly</span></li>
                                    <li><i class="ti ti-certificate"></i> <span>Provider-specific - see only what's relevant to your certification</span></li>
                                    <li><i class="ti ti-refresh"></i> <span>Always current - updates deployed immediately</span></li>
                                    <li><i class="ti ti-device-laptop"></i> <span>Responsive - works on all your devices</span></li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="ti ti-heartbeat about-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="about-section mb-5">
                <div class="section-header d-flex align-items-center">
                    <i class="ti ti-user-circle me-3"></i>
                    <h2>About the Developer</h2>
                </div>
                <div class="card about-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <div class="developer-profile">
                                    <i class="ti ti-user-circle"></i>
                                    <h3 class="mt-3">[Your Name]</h3>
                                    <p class="text-muted">[Your Role/Title]</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <p>Hi! I'm [Your Name], the developer behind this site. I'm a [your profession/background] with a passion for emergency medicine and web development.</p>
                                <p>I created this platform to address the need for accessible, user-friendly EMS protocols that can be easily referenced in the field or during training.</p>
                                <div class="contact-info mt-4">
                                    <div class="contact-item">
                                        <i class="ti ti-mail"></i>
                                        <span>[Your Email]</span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="ti ti-brand-github"></i>
                                        <span><a href="https://github.com/[your-username]" target="_blank">github.com/[your-username]</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="about-section mb-5">
                <div class="section-header d-flex align-items-center">
                    <i class="ti ti-stack me-3"></i>
                    <h2>Technology Stack</h2>
                </div>
                <div class="card about-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="tech-category">
                                    <h3><i class="ti ti-browser"></i> Frontend</h3>
                                    <ul class="tech-list">
                                        <li><span class="tech-badge">HTML5</span> Modern markup</li>
                                        <li><span class="tech-badge">CSS3</span> Advanced styling</li>
                                        <li><span class="tech-badge">Bootstrap 5</span> Responsive framework</li>
                                        <li><span class="tech-badge">jQuery</span> Enhanced interactivity</li>
                                        <li><span class="tech-badge">Tabler Icons</span> SVG icon library</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tech-category">
                                    <h3><i class="ti ti-server"></i> Backend</h3>
                                    <ul class="tech-list">
                                        <li><span class="tech-badge">PHP 8.1+</span> Server-side processing</li>
                                        <li><span class="tech-badge">MySQL 8.0+</span> Database engine</li>
                                        <li><span class="tech-badge">TinyMCE</span> Rich text editing</li>
                                        <li><span class="tech-badge">PDO</span> Database access layer</li>
                                        <li><span class="tech-badge">SiteGround</span> Hosting platform</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tech-diagram mt-4">
                            <h3>Key Features</h3>
                            <div class="row text-center">
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="feature-circle">
                                        <i class="ti ti-devices"></i>
                                    </div>
                                    <h4>Responsive</h4>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="feature-circle">
                                        <i class="ti ti-search"></i>
                                    </div>
                                    <h4>Searchable</h4>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="feature-circle">
                                        <i class="ti ti-filter"></i>
                                    </div>
                                    <h4>Filterable</h4>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="feature-circle">
                                        <i class="ti ti-sitemap"></i>
                                    </div>
                                    <h4>Interactive</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="about-section mb-5">
                <div class="section-header d-flex align-items-center">
                    <i class="ti ti-license me-3"></i>
                    <h2>Licensing & Open Source</h2>
                </div>
                <div class="card about-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p>This website code is licensed under the <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" class="text-primary">Creative Commons Attribution 4.0 International License</a>.</p>
                                <p>The source code for this project is available on <a href="https://github.com/[your-username]/[your-repository]" target="_blank" class="text-primary">GitHub</a>. Feel free to fork, modify, and use it as a template for your own EMS protocol systems.</p>
                                <p class="alert alert-info mt-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Note:</strong> While the website code is open source, the actual protocol content may be subject to different licensing terms depending on your local medical authority.
                                </p>
                            </div>
                            <div class="col-md-4 text-center">
                                <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" class="cc-license">
                                    <img src="https://mirrors.creativecommons.org/presskit/buttons/88x31/svg/by.svg" alt="CC BY 4.0 License" height="60">
                                </a>
                                <p class="mt-3 license-text">CC BY 4.0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-lg btn-primary">
                    <i class="ti ti-arrow-left me-2"></i> Back to Protocols
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Add these styles to the page -->
<style>
    /* About page specific styles */
    .divider-custom {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 1.5rem 0;
    }
    
    .divider-line {
        width: 100px;
        height: 4px;
        background-color: var(--primary-color);
        border-radius: 2px;
        opacity: 0.3;
    }
    
    .divider-icon {
        color: var(--primary-color);
        font-size: 1.5rem;
        margin: 0 15px;
    }
    
    .about-section {
        margin-bottom: 3rem;
    }
    
    .section-header {
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid rgba(16, 110, 158, 0.2);
    }
    
    .section-header i {
        font-size: 2rem;
        color: var(--primary-color);
    }
    
    .section-header h2 {
        font-size: 1.75rem;
        font-weight: 600;
        margin: 0;
        color: #2c3e50;
    }
    
    .about-card {
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: none;
        overflow: hidden;
    }
    
    .info-box {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .info-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .info-box-icon {
        background-color: var(--primary-color);
        color: white;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
    }
    
    .info-box h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .about-icon {
        color: var(--primary-color);
        font-size: 8rem;
        opacity: 0.8;
    }
    
    .feature-list {
        list-style: none;
        padding-left: 0;
    }
    
    .feature-list li {
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }
    
    .feature-list li i {
        color: var(--primary-color);
        margin-right: 0.75rem;
        font-size: 1.25rem;
    }
    
    .developer-profile {
        background-color: #f8f9fa;
        padding: 2rem;
        border-radius: 1rem;
    }
    
    .developer-profile i {
        font-size: 6rem;
        color: var(--primary-color);
    }
    
    .contact-info {
        margin-top: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .contact-item i {
        color: var(--primary-color);
        margin-right: 0.75rem;
        font-size: 1.25rem;
    }
    
    .contact-item a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .contact-item a:hover {
        text-decoration: underline;
    }
    
    .tech-category {
        margin-bottom: 2rem;
    }
    
    .tech-category h3 {
        border-bottom: 2px solid rgba(16, 110, 158, 0.2);
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    
    .tech-category h3 i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }
    
    .tech-list {
        list-style: none;
        padding-left: 0;
    }
    
    .tech-list li {
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .tech-badge {
        background-color: var(--primary-color);
        color: white;
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 0.75rem;
        min-width: 90px;
        display: inline-block;
        text-align: center;
    }
    
    .feature-circle {
        width: 80px;
        height: 80px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2.5rem;
        box-shadow: 0 4px 10px rgba(16, 110, 158, 0.3);
    }
    
    .tech-diagram h4 {
        font-size: 1rem;
        font-weight: 600;
    }
    
    .cc-license {
        display: inline-block;
        transition: transform 0.3s ease;
    }
    
    .cc-license:hover {
        transform: scale(1.1);
    }
    
    .license-text {
        font-weight: 600;
        color: #555;
    }
    
    @media (max-width: 768px) {
        .developer-profile {
            max-width: 250px;
            margin: 0 auto;
        }
    }
</style>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>