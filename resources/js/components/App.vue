<template>
    <div>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <div class="navbar-nav">
                    <router-link class="nav-item nav-link" :to="{name: 'home'}">Main Page</router-link>
                    <router-link class="nav-item nav-link" :to="{name: 'desks'}">Desks</router-link>
                </div>
            </div>
        </nav>
        <div>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {

}
</script>
