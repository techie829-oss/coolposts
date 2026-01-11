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

# Block authentication pages
Disallow: /login
Disallow: /register
Disallow: /forgot-password
Disallow: /reset-password
Disallow: /verify-email
Disallow: /email/verify

# Block private areas
Disallow: /dashboard
Disallow: /admin
Disallow: /user/*
Disallow: /subscriptions
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

Sitemap: https://{{ $platformName === 'CoolPosts' ? 'www.coolposts.site' : 'coolposts.site' }}/sitemap.xml
