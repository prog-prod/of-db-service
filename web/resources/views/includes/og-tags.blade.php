<meta property="og:title" content="{{$meta_title}}">
<meta property="og:description" content="{{$meta_description}}">
<meta property="og:type" content="article">
<meta property="og:image" content="{{$photo_of_first_result}}">
<meta property="og:url" content="{{$current_url}}">
<meta property="og:site_name" content="OnlyGirls">
<meta property="og:locale" content="en">

<script type="application/ld+json">
            [
                {
                    "@context": "http://schema.org",
                    "@type": "NewsArticle",
                    "headline": "{{$meta_title}}",
                    "image": [
                        "{{$photo_of_first_result}}"
                    ],
                    "datePublished": "{{$date_published}}",
                    "dateModified": "{{$date_update_of_first_result}}",
                    "author": {
                        "@type": "Organization",
                        "name": "OnlyGirls"
                    },
                    "mainEntityOfPage": "{{$current_url}}"
                },
                {
                    "@context": "http://schema.org",
                    "@type": "BreadcrumbList",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "item": {
                                "@id": "https://onlygirls.com/",
                                "name": "OnlyFans"
                            }
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "item": {
                                "@id": "{{$current_url}}",
                                "name": "{{$current_page_key}}"
                            }
                        },
                        {
                            "@type": "ListItem",
                            "position": 3,
                            "item": {
                                "@id": "{{$current_url}}",
                                "name": "profiles"
                            }
                        }
                    ]
                }
            ]
        </script>
