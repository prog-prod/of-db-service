<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><base href=""/>
    <title>Admin Panel | Only Girls</title>
    <meta charset="utf-8" />
    <meta name="description" content="Admin Panel - Only Girls" />
    <meta name="keywords" content="only girls" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
    <link rel="stylesheet" href="{{asset('/css/admin/splash-screen.css')}}">
    <!-- Scripts -->
    @routes(['admin'])
   @vite(['resources/js/Admin/admin.js','resources/scss/admin.scss', "resources/js/Admin/Pages/{$page['component']}.vue"])
   @inertiaHead
</head>

<body class="page-loading">
<!--begin::Theme mode setup on page load-->
<script>
   let themeMode = "system";

   if (localStorage.getItem("kt_theme_mode_value")) {
       themeMode = localStorage.getItem("kt_theme_mode_value");
   }

   if (themeMode === "system") {
       themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
   }

   document.documentElement.setAttribute("data-bs-theme", themeMode);
</script>
<!--end::Theme mode setup on page load-->
<!--begin::Loading markup-->
<div id="splash-screen" class="splash-screen">
   <img src="{{asset('/admin-panel/media/logos/default-dark.svg')}}" class="dark-logo" alt="Metronic dark logo" />
   <img src="{{asset('/admin-panel/media/logos/default.svg')}}" class="light-logo" alt="Metronic light logo" />
   <span>Loading ...</span>
</div>
<!--end::Loading markup-->
@inertia
</body>
</html>
