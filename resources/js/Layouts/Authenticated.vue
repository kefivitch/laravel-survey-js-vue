<template>

    <div class="position-absolute top-0 min-vh-100 min-vw-100 bg-light">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
            <div class="container-fluid">
                <Link :href="route('dashboard')">
                    <breeze-application-logo style="width: 30px;" />
                </Link>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <Link class="nav-link" :class="{ 'active': $page.url.endsWith('dashboard') }" aria-current="page" :href="route('dashboard')">Dashboard</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" :class="{ 'active': $page.url.endsWith('surveys') }" aria-current="page" :href="route('surveys')">Surveys List</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" :class="{ 'active': $page.url.endsWith('/create-survey') }" aria-current="page" :href="route('create-survey')">Create Survey</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" :class="{ 'active': $page.url.endsWith('/users') }" aria-current="page" :href="route('users')">Users List</Link>
                        </li>
                    </ul>

                    <div class="d-flex">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $page.props.auth.user.name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <Link class="dropdown-item" :href="route('logout')" method="post">
                                            Log Out
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div :class="$page.props.is_creator ? '' : 'container'">
        <!--<div class="container">-->
            <slot />
        </div>
    </div>

</template>

<script>
    import BreezeApplicationLogo from '@/Components/ApplicationLogo'
    import { Link } from '@inertiajs/inertia-vue'

    export default {
        components: {
            BreezeApplicationLogo,
            Link,
        },
    }
</script>
