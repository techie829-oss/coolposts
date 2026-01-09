<x-app-layout>
    <x-slot name="title">Blog Post Templates - {{ $brandingSettings->brand_name ?? 'CoolPosts' }}</x-slot>

    <x-slot name="head">
        <meta name="description" content="Choose from professional blog post templates including tutorials, how-to guides, reviews, news articles, lists, and case studies. Create engaging content quickly with our pre-built templates.">
        <meta name="keywords" content="blog templates, content templates, writing templates, tutorial template, how-to guide, review template, news template, list template, case study template">
        <meta name="robots" content="index, follow">

        <!-- Open Graph -->
        <meta property="og:title" content="Blog Post Templates - {{ $brandingSettings->brand_name ?? 'CoolPosts' }}">
        <meta property="og:description" content="Choose from professional blog post templates including tutorials, how-to guides, reviews, news articles, lists, and case studies.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/blog-posts/templates') }}">
        <meta property="og:site_name" content="{{ $brandingSettings->brand_name ?? 'CoolPosts' }}">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Blog Post Templates - {{ $brandingSettings->brand_name ?? 'CoolPosts' }}">
        <meta name="twitter:description" content="Choose from professional blog post templates including tutorials, how-to guides, reviews, news articles, lists, and case studies.">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url('/blog-posts/templates') }}">
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Blog Post Templates') }}
            </h2>
            @auth
                <a href="{{ route('blog.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Custom Post
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign Up to Create
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Introduction -->
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-8 mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Choose a Template to Get Started</h3>
                <p class="text-gray-600 text-lg">
                    Use these pre-built templates to create professional blog posts quickly. Each template includes
                    proper structure, formatting, and examples that you can customize for your content.
                </p>
            </div>

            <!-- Template Categories -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Tutorial Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Tutorial Template</h4>
                                <p class="text-sm text-gray-500">Step-by-step guides</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Perfect for creating comprehensive tutorials with numbered steps, code examples, and clear explanations.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Numbered steps and sections
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Code block formatting
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Prerequisites and conclusion
                            </div>
                        </div>
                        <button onclick="loadTemplate('tutorial')"
                            class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 px-4 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>

                <!-- How-to Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tools text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">How-to Template</h4>
                                <p class="text-sm text-gray-500">Practical instructions</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Ideal for practical guides that teach readers how to accomplish specific tasks or solve problems.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Clear objectives
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Step-by-step process
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Troubleshooting tips
                            </div>
                        </div>
                        <button onclick="loadTemplate('howto')"
                            class="w-full bg-gradient-to-r from-green-500 to-teal-600 text-white py-2 px-4 rounded-lg hover:from-green-600 hover:to-teal-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>

                <!-- Review Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Review Template</h4>
                                <p class="text-sm text-gray-500">Product and service reviews</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Great for reviewing products, services, or tools with structured evaluation criteria and ratings.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Rating system
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Pros and cons
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Final verdict
                            </div>
                        </div>
                        <button onclick="loadTemplate('review')"
                            class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-2 px-4 rounded-lg hover:from-orange-600 hover:to-red-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>

                <!-- News Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-newspaper text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">News Template</h4>
                                <p class="text-sm text-gray-500">Current events and updates</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Perfect for reporting on current events, industry updates, or breaking news with proper journalistic structure.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Lead paragraph
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Background context
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Future implications
                            </div>
                        </div>
                        <button onclick="loadTemplate('news')"
                            class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-2 px-4 rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>

                <!-- List Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-rose-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-list-ol text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">List Template</h4>
                                <p class="text-sm text-gray-500">Curated lists and rankings</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Excellent for creating top 10 lists, best-of compilations, or curated resource collections.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Numbered items
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Brief descriptions
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Summary conclusion
                            </div>
                        </div>
                        <button onclick="loadTemplate('list')"
                            class="w-full bg-gradient-to-r from-pink-500 to-rose-600 text-white py-2 px-4 rounded-lg hover:from-pink-600 hover:to-rose-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>

                <!-- Case Study Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Case Study Template</h4>
                                <p class="text-sm text-gray-500">Real-world examples</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Perfect for analyzing real-world examples, success stories, or detailed project breakdowns.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Problem statement
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Solution approach
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Results and lessons
                            </div>
                        </div>
                        <button onclick="loadTemplate('casestudy')"
                            class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 text-white py-2 px-4 rounded-lg hover:from-cyan-600 hover:to-blue-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>

                <!-- Business Page Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-briefcase text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Business Page Template</h4>
                                <p class="text-sm text-gray-500">Company information</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Professional template for showcasing your business, services, and company information.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                About section
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Services overview
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Contact information
                            </div>
                        </div>
                        <button onclick="loadTemplate('businesspage')"
                            class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 text-white py-2 px-4 rounded-lg hover:from-indigo-600 hover:to-blue-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>

                <!-- Company Portfolio Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-building text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Company Portfolio Template</h4>
                                <p class="text-sm text-gray-500">Corporate showcase</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Showcase your company's projects, achievements, and portfolio in a professional format.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Project showcase
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Team and capabilities
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Client testimonials
                            </div>
                        </div>
                        <button onclick="loadTemplate('companyportfolio')"
                            class="w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white py-2 px-4 rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>

                <!-- Personal Portfolio Template -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-rose-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-circle text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Personal Portfolio Template</h4>
                                <p class="text-sm text-gray-500">Personal showcase</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Create a professional personal portfolio highlighting your skills, projects, and achievements.
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                About me
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Skills & expertise
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Projects showcase
                            </div>
                        </div>
                        <button onclick="loadTemplate('personalportfolio')"
                            class="w-full bg-gradient-to-r from-pink-500 to-rose-600 text-white py-2 px-4 rounded-lg hover:from-pink-600 hover:to-rose-700 transition-all duration-300 font-medium">
                            <i class="fas fa-download mr-2"></i>Use Template
                        </button>
                    </div>
                </div>
            </div>

            <!-- Template Preview Modal -->
            <div id="templateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Template Preview</h3>
                                <button onclick="closeTemplateModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div id="templateContent" class="prose prose-lg max-w-none">
                                <!-- Template content will be loaded here -->
                            </div>
                        </div>
                        <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                            <button onclick="closeTemplateModal()"
                                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                Cancel
                            </button>
                            <button onclick="useTemplate()"
                                class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>Use This Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include marked.js for markdown parsing -->
    <script src="https://cdn.jsdelivr.net/npm/marked@9.1.6/marked.min.js"></script>

    <script>
        let currentTemplate = null;
        let currentTemplateData = null;

        const templates = {
            tutorial: {
                title: 'Complete Tutorial Template',
                content: `# [Your Tutorial Title] | Step-by-Step Guide

[Write a compelling introduction that explains what readers will learn and why it's important.]

## Prerequisites

Before we begin, ensure you have:

- [Requirement 1]
- [Requirement 2]
- [Requirement 3]
- [Any other prerequisites]

## Step 1: [First Step Title]

[Explain the first step in detail]

\`\`\`bash
# Example command
command example
\`\`\`

[Additional explanation or context]

## Step 2: [Second Step Title]

[Explain the second step in detail]

\`\`\`bash
# Another example command
another command example
\`\`\`

[Continue with more steps as needed]

## Advanced Configuration (Optional)

[Explain any advanced options or configurations]

\`\`\`config
# Configuration example
setting = value
\`\`\`

## Troubleshooting Common Issues

### Issue 1: [Common Problem]
**Solution:** [How to fix it]

### Issue 2: [Another Common Problem]
**Solution:** [How to fix it]

## Monitoring and Maintenance

[Explain how to monitor and maintain what was set up]

\`\`\`bash
# Monitoring command
monitor command
\`\`\`

## Conclusion

Congratulations! You've successfully [completed the tutorial objective].

Remember to:
- [Important reminder 1]
- [Important reminder 2]
- [Important reminder 3]

For production environments, consider [additional considerations].`
            },
            howto: {
                title: 'How-to Guide Template',
                content: `# How to [Specific Task or Goal]

[Brief introduction explaining what this guide will help readers accomplish]

## What You'll Need

- [Tool/Resource 1]
- [Tool/Resource 2]
- [Tool/Resource 3]

## Step-by-Step Instructions

### Step 1: [First Action]
[Detailed explanation of the first step]

\`\`\`bash
# Command or code example
example command
\`\`\`

### Step 2: [Second Action]
[Detailed explanation of the second step]

\`\`\`bash
# Another command example
another example
\`\`\`

[Continue with more steps as needed]

## Alternative Methods

### Method 1: [Alternative approach]
[Explain alternative way to accomplish the same goal]

### Method 2: [Another alternative]
[Explain another alternative approach]

## Troubleshooting

**Problem:** [Common issue]
**Solution:** [How to resolve it]

**Problem:** [Another common issue]
**Solution:** [How to resolve it]

## Tips for Success

- [Tip 1]
- [Tip 2]
- [Tip 3]

## What's Next?

[Suggest related topics or next steps for readers]`
            },
            review: {
                title: 'Product/Service Review Template',
                content: `# [Product/Service Name] Review: [Brief Description]

[Introduction explaining what you're reviewing and why it matters]

## Quick Overview

- **Product:** [Product name]
- **Category:** [Product category]
- **Price:** [Price range]
- **Rating:** [X/5 stars]
- **Best for:** [Target audience]

## What I Liked

### ✅ [Positive aspect 1]
[Detailed explanation]

### ✅ [Positive aspect 2]
[Detailed explanation]

### ✅ [Positive aspect 3]
[Detailed explanation]

## What Could Be Better

### ❌ [Negative aspect 1]
[Detailed explanation]

### ❌ [Negative aspect 2]
[Detailed explanation]

## Key Features

### [Feature 1]
[Description and benefits]

### [Feature 2]
[Description and benefits]

### [Feature 3]
[Description and benefits]

## Performance Analysis

[Discuss performance, speed, reliability, etc.]

\`\`\`
Performance Metrics:
- Speed: [Rating/Description]
- Reliability: [Rating/Description]
- Ease of use: [Rating/Description]
\`\`\`

## Pricing and Value

[Discuss pricing structure and whether it provides good value]

## Alternatives to Consider

### [Alternative 1]
[Brief comparison]

### [Alternative 2]
[Brief comparison]

## Final Verdict

[Overall assessment and recommendation]

**Rating: [X]/5**

[Summary of who should buy/use this and who should avoid it]

## Where to Buy

[Links or information about where readers can purchase/access the product/service]`
            },
            news: {
                title: 'News Article Template',
                content: `# [Headline: Main News Story]

[Lead paragraph that captures the most important information - who, what, when, where, why, and how]

## Breaking News

[Immediate details and developments]

## Background Context

[Provide necessary background information to help readers understand the full picture]

\`\`\`
Key Facts:
- [Fact 1]
- [Fact 2]
- [Fact 3]
\`\`\`

## What Happened

[Detailed explanation of the events]

## Why It Matters

[Explain the significance and implications]

## Expert Opinions

[Quote or reference expert opinions if available]

> "[Expert quote]"
> — [Expert name], [Title/Organization]

## Market/Industry Impact

[Discuss how this affects the market, industry, or relevant sector]

## What's Next

[Discuss future developments, next steps, or what to watch for]

## Related Stories

- [Related story 1]
- [Related story 2]
- [Related story 3]

## Stay Updated

[Information about how readers can follow this story]`
            },
            list: {
                title: 'Top 10 List Template',
                content: `# Top 10 [Category]: [Brief Description]

[Introduction explaining what this list covers and why it's valuable]

## 10. [Item Name]

[Description of the 10th item]

**Why it's great:** [Key benefit or feature]

**Best for:** [Who would benefit most]

## 9. [Item Name]

[Description of the 9th item]

**Why it's great:** [Key benefit or feature]

**Best for:** [Who would benefit most]

[Continue with items 8-2...]

## 1. [Top Item Name]

[Description of the #1 item]

**Why it's the best:** [Key benefits and features]

**Best for:** [Who would benefit most]

## Honorable Mentions

[Items that didn't make the top 10 but are still worth considering]

## How We Ranked

[Explain the criteria used for ranking]

## Summary

[Brief recap of the top choices]

## What's Your Pick?

[Encourage reader engagement and discussion]

---

*This list was last updated on [Date]. Rankings and information may change over time.*`
            },
            casestudy: {
                title: 'Case Study Template',
                content: `# Case Study: [Company/Project Name] - [Brief Description]

[Executive summary of the case study and key outcomes]

## The Challenge

[Describe the problem or challenge that needed to be solved]

### Background
[Provide context about the company, situation, or project]

### Key Problems
- [Problem 1]
- [Problem 2]
- [Problem 3]

## The Solution

[Explain the approach taken to solve the problem]

### Strategy
[Describe the overall strategy]

### Implementation
[Detail how the solution was implemented]

\`\`\`
Timeline:
- [Month/Year]: [Action taken]
- [Month/Year]: [Action taken]
- [Month/Year]: [Action taken]
\`\`\`

## The Results

[Present the outcomes and results]

### Quantitative Results
- [Metric 1]: [Result]
- [Metric 2]: [Result]
- [Metric 3]: [Result]

### Qualitative Results
- [Benefit 1]
- [Benefit 2]
- [Benefit 3]

## Key Success Factors

[Identify what made this successful]

- [Factor 1]
- [Factor 2]
- [Factor 3]

## Lessons Learned

[Share insights and takeaways]

### What Worked Well
[Successful aspects]

### What Could Be Improved
[Areas for future improvement]

## Future Implications

[Discuss how this case study impacts future decisions or industry trends]

## Conclusion

[Summarize the key takeaways and success story]

---

*This case study demonstrates [main lesson or insight]. For more information, [contact details or next steps].*`
            },
            businesspage: {
                title: 'Business Page Template',
                content: `# [Business Name] - [Tagline/Description]

[Brief introduction about your business and what it does]

## About Us

[Detailed information about your business, mission, values, and history]

### Our Mission
[Explain your company's mission and purpose]

### Our Vision
[Describe your vision for the future]

### Company Values
- [Value 1]: [Description]
- [Value 2]: [Description]
- [Value 3]: [Description]

## Our Services

### [Service 1]
[Description of the first service you offer]

**Benefits:**
- [Benefit 1]
- [Benefit 2]
- [Benefit 3]

### [Service 2]
[Description of the second service you offer]

**Benefits:**
- [Benefit 1]
- [Benefit 2]
- [Benefit 3]

### [Service 3]
[Description of the third service you offer]

**Benefits:**
- [Benefit 1]
- [Benefit 2]
- [Benefit 3]

## Why Choose Us?

### [Reason 1]
[Explanation of what makes your business unique]

### [Reason 2]
[Another unique selling point]

### [Reason 3]
[Additional competitive advantage]

## Our Expertise

[Describe your areas of expertise and industry knowledge]

\`\`\`
Key Strengths:
- [Strength 1]
- [Strength 2]
- [Strength 3]
\`\`\`

## Industries We Serve

- [Industry 1]: [How you help]
- [Industry 2]: [How you help]
- [Industry 3]: [How you help]

## Contact Information

**Address:** [Your business address]

**Phone:** [Phone number]

**Email:** [Email address]

**Business Hours:**
- Monday - Friday: [Hours]
- Saturday: [Hours]
- Sunday: [Hours]

## Get in Touch

[Call-to-action encouraging visitors to contact you]

> Ready to work with us? Contact us today to discuss how we can help your business grow!

## Social Media

- [Link to your social media profiles]
- [Additional contact methods]

---

*Last updated: [Date]. All rights reserved.*`
            },
            companyportfolio: {
                title: 'Company Portfolio Template',
                content: `# [Company Name] Portfolio

[Professional introduction to your company and its work]

## Company Overview

[Brief overview of your company, years in business, and specialty areas]

### About [Company Name]
[Detailed information about your company]

### Founding Story
[Share the story of how your company was founded and its journey]

### Team Overview
[Information about your team size, expertise, and culture]

## Our Services

### [Service Category 1]
[Description]

### [Service Category 2]
[Description]

### [Service Category 3]
[Description]

## Featured Projects

### Project 1: [Project Name]
**Client:** [Client name]
**Industry:** [Industry]
**Duration:** [Timeline]

[Project description]

**Challenge:**
[Describe the challenges faced]

**Solution:**
[Explain how you solved it]

**Results:**
- [Result metric 1]
- [Result metric 2]
- [Result metric 3]

### Project 2: [Project Name]
**Client:** [Client name]
**Industry:** [Industry]
**Duration:** [Timeline]

[Project description]

**Key Achievements:**
- [Achievement 1]
- [Achievement 2]
- [Achievement 3]

### Project 3: [Project Name]
**Client:** [Client name]
**Industry:** [Industry]
**Duration:** [Timeline]

[Project description]

**Technologies Used:**
- [Technology 1]
- [Technology 2]
- [Technology 3]

## Technologies & Tools

### Core Technologies
- [Technology 1]: [Description]
- [Technology 2]: [Description]
- [Technology 3]: [Description]

### Development Tools
- [Tool 1]
- [Tool 2]
- [Tool 3]

## Our Expertise

### Technical Skills
\`\`\`
- [Skill 1]: Expert
- [Skill 2]: Advanced
- [Skill 3]: Advanced
- [Skill 4]: Proficient
\`\`\`

### Industries
- [Industry 1]
- [Industry 2]
- [Industry 3]

## Client Testimonials

> "[Testimonial quote from a satisfied client]"
> — [Client Name], [Position] at [Company]

> "[Another testimonial quote]"
> — [Client Name], [Position] at [Company]

## Our Process

### 1. Discovery
[Describe your initial project discovery phase]

### 2. Planning
[Explain how you plan projects]

### 3. Execution
[Detail your execution process]

### 4. Delivery
[Describe how you deliver results]

## Awards & Recognition

- [Year]: [Award/Certification name]
- [Year]: [Award/Certification name]
- [Year]: [Award/Certification name]

## Contact Us

Ready to start your next project?

**Email:** [Email]
**Phone:** [Phone]
**Website:** [Website]

---

*View our full portfolio at [Website URL]. Let's create something amazing together.*`
            },
            personalportfolio: {
                title: 'Personal Portfolio Template',
                content: `# [Your Name] - [Your Title]

[A compelling introduction about yourself and what you do]

## About Me

[Personal introduction and professional background]

### Professional Summary
[Brief summary of your professional experience and expertise]

### What I Do
[Describe your role, skills, and what you specialize in]

## Skills & Expertise

### Technical Skills
- **[Skill 1]**: [Years of experience or proficiency level]
  - [Sub-skill or area of expertise]

- **[Skill 2]**: [Years of experience or proficiency level]
  - [Sub-skill or area of expertise]

- **[Skill 3]**: [Years of experience or proficiency level]
  - [Sub-skill or area of expertise]

### Tools & Technologies
\`\`\`
Proficient in:
- [Technology/Tool 1]
- [Technology/Tool 2]
- [Technology/Tool 3]
- [Technology/Tool 4]
\`\`\`

### Soft Skills
- [Skill 1]
- [Skill 2]
- [Skill 3]
- [Skill 4]

## Work Experience

### [Job Title] at [Company Name]
**Duration:** [Start Date] - [End Date or Present]

[Brief description of your role and responsibilities]

**Key Achievements:**
- [Achievement 1]
- [Achievement 2]
- [Achievement 3]

**Technologies Used:** [List technologies]

### [Previous Job Title] at [Previous Company]
**Duration:** [Start Date] - [End Date]

[Brief description of your role]

**Notable Projects:**
- [Project 1]
- [Project 2]

## Education

### [Degree] in [Field]
**[University/Institution Name]**
**Duration:** [Year] - [Year]
**GPA:** [If applicable]

**Relevant Coursework:**
- [Course 1]
- [Course 2]
- [Course 3]

### Certifications
- [Certification 1] - [Year]
- [Certification 2] - [Year]
- [Certification 3] - [Year]

## Featured Projects

### Project 1: [Project Name]
**Type:** [Personal/Professional/Open Source]
**Year:** [Year]

[Project description]

**Technologies:** [Technologies used]
**Live Demo:** [Link]
**GitHub:** [Link]

### Project 2: [Project Name]
**Type:** [Personal/Professional]
**Year:** [Year]

[Project description]

**Key Features:**
- [Feature 1]
- [Feature 2]
- [Feature 3]

**GitHub:** [Link]

### Project 3: [Project Name]
**Type:** [Personal/Professional]
**Year:** [Year]

[Project description]

**Technologies:** [Technologies used]

## Testimonials & Recommendations

> "[Recommendation/testimonial from a colleague or client]"
> — [Name], [Position] at [Company]

> "[Another recommendation]"
> — [Name], [Position] at [Company]

## Interests & Hobbies

[Share your personal interests and hobbies outside of work]

- [Interest 1]
- [Interest 2]
- [Interest 3]

## Get in Touch

I'm always open to discussing new opportunities, interesting projects, or just having a chat!

**Email:** [Email]
**LinkedIn:** [Profile]
**GitHub:** [Profile]
**Twitter:** [Handle]
**Portfolio:** [Website]

---

*Let's connect and create something amazing together!*`
            }
        };

        function loadTemplate(templateType) {
            currentTemplate = templateType;
            currentTemplateData = templates[templateType];

            document.getElementById('modalTitle').textContent = currentTemplateData.title;
            document.getElementById('templateContent').innerHTML = marked.parse(currentTemplateData.content);
            document.getElementById('templateModal').classList.remove('hidden');
        }

        function closeTemplateModal() {
            document.getElementById('templateModal').classList.add('hidden');
            currentTemplate = null;
            currentTemplateData = null;
        }

        function useTemplate() {
            if (currentTemplate && currentTemplateData) {
                // Store template data in localStorage
                localStorage.setItem('blogTemplate', JSON.stringify({
                    type: currentTemplate,
                    title: currentTemplateData.title,
                    content: currentTemplateData.content
                }));

                // Redirect based on authentication status
                @auth
                    window.location.href = '{{ route("blog.create") }}';
                @else
                    // Redirect to register page for guest users
                    window.location.href = '{{ route("register") }}';
                @endauth
            }
        }

        // Check for template data on page load
        document.addEventListener('DOMContentLoaded', function() {
            const templateData = localStorage.getItem('blogTemplate');
            if (templateData) {
                const data = JSON.parse(templateData);
                // You can auto-fill the form here if needed
                localStorage.removeItem('blogTemplate');
            }
        });
    </script>
</x-app-layout>
