<template>
  <div id="app" class="tracking-tight font-semibold text-activeText">
    <div v-if="layout === 'home'">
      <Header :username="getUsername" />
      <MobileMenu v-if="menu" />
      <div class="flex" :class="{ 'sm:hidden': menu }">
        <Sidebar :openSidebar="statusSidebar" @toggleSidebar="toggleSidebar" />
        <div
          class="h-screen pt-10 sm:w-full sm:pt-10 sm:bg-[#F6F8FE] w-full"
        >
        <!-- h-full  => esto hace que sea centrado y full -->
          <!-- <div class="max-h-[39rem] px-16 sm:px-8 py-8 overflow-y-auto">
            <router-view />
          </div> -->
          <div class="h-full px-12 sm:px-8 py-4 overflow-y-auto">
            <router-view />
          </div>
        </div>
      </div>
    </div>
    <div v-else-if="layout === 'registro'">
      <RegisterBar />
      <div
        class="flex pt-36 sm:pt-40 sm:pt-10 sm:pb-12 sm:px-8 sm:bg-[#F6F8FE] justify-center"
      >
        <router-view />
      </div>
    </div>
    <div v-else-if="layout === 'prueba2'">


        <router-view />

    </div>
    <div v-else-if="layout === 'login'">
      <!-- <Header /> -->
      <!-- <Sidebar :openSidebar="openSidebar" @toggleSidebar="toggleSidebar" /> -->
      <!-- <RegisterBar /> -->
      <div
        class="flex pt-36 sm:pt-40 sm:pt-10 sm:pb-12 sm:px-8 sm:bg-[#F6F8FE] justify-center"
      >
        <router-view />
      </div>
    </div>
    <div v-else-if="layout === 'success-register'">
      <router-view />
    </div>
  </div>
</template>

<script>
import RegisterBar from "./components/RegisterBar.vue";
import Header from "./components/Layout/Header.vue";
import Sidebar from "./components/Layout/Sidebar.vue";
import MobileMenu from "./components/Layout/MobileMenu.vue";
export default {
  components: {
    RegisterBar,
    Header,
    Sidebar,
    MobileMenu,
  },
  data: function () {
    return {
      openSidebar: true,
      // username   : ""
    };
  },
  methods: {
    toggleSidebar: function () {
      this.$store.state.sidebar = !this.$store.state.sidebar;
      // this.openSidebar = !this.openSidebar;
    },
    handleResize: function() {
      window.innerWidth <= 1000 && (this.$store.state.sidebar = false);
      window.innerWidth > 1000 && (this.$store.state.sidebar = true);

      document.body.style.zoom = "90%";
    },

  },
  computed: {
    layout: function () {
      return this.$route.meta.layout;
    },
    // closeSidebar: function () {
    //   return this.$route.meta.sidebarOpen;
    // },
    // Statussidebar: function (){
    //   return this.openSidebar
    // },
    statusSidebar: function (){
      return this.$store.state.sidebar;
    },
    menu: function () {
      return this.$store.state.menu;
    },
    getUsername: function() {

      // return " "
      return this.$store.state.user.data.name +" "+ this.$store.state.user.data.lastname

    }
  },
  mounted: function() {
    window.addEventListener('resize', this.handleResize);
    this.handleResize();
    this.$store.dispatch('getUserName');

    // this.$store.state.sidebar = false

  },
};
</script>
