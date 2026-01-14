User-agent: *

# ALLOW public pages (must come before Disallow rules)
Allow: /$
Allow: /blog-posts
Allow: /blog-posts/*
Allow: /how-we-work

# Allow legal & trust pages
Allow: /terms-of-service
Allow: /privacy-policy
Allow: /cookie-policy
Allow: /refund-policy
Allow: /dmca
Allow: /gdpr

# Note: Auth pages (/login, /register) and /subscriptions are crawlable
# but use noindex meta tags to prevent indexing

# Block password reset and email verification pages
Disallow: /forgot-password
Disallow: /reset-password
Disallow: /verify-email
Disallow: /email/verify

# Block private areas
Disallow: /dashboard
Disallow: /admin
Disallow: /user/*
Disallow: /withdrawals
Disallow: /analytics
Disallow: /realtime
Disallow: /links/
Disallow: /profile

# Block API and development tools
Disallow: /api
Disallow: /sanctum/*
Disallow: /telescope
Disallow: /horizon
Disallow: /_ignition
Disallow: /health
Disallow: /debug

# Block tracking endpoints
Disallow: /*/track-visitor
Disallow: /*/track-leave
Disallow: /monetize/

# Block query parameters (SEO best practice)
Disallow: /*?*

Sitemap: {{ url('sitemap.xml') }}
