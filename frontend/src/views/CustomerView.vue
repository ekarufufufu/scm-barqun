<template>
    <v-app>
        <LeftBar/>
        <Customers/>
    </v-app>
</template>

<script>
import LeftBar from '@/components/LeftBar.vue'
import Customers from '@/views/pages/customer/Index.vue'
export default {
    components:{
        LeftBar,
        Customers,

    }
}
</script>

<style>

</style>