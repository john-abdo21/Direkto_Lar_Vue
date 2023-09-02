<template>

  <div id="app" class="tracking-tight font-semibold text-activeText">
    <Header :username="getUsername" />
      <MobileMenu v-if="menu" />
      <div class="flex" :class="{ 'sm:hidden': menu }">
        <Sidebar
        :openSidebar="statusSidebar"
        @toggleSidebar="toggleSidebar"
        />
        <div
          class="h-screen pt-10 sm:w-full sm:pt-10 sm:bg-[#F6F8FE] w-full"
        >
          <div class="h-full px-12 sm:px-4 py-8 overflow-y-auto">
            <router-view />
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import Header from "../Layout/Header.vue";
import Sidebar from "../Layout/Sidebar.vue";
import MobileMenu from "../Layout/MobileMenu.vue";

export default {
  components: {
    Header,
    Sidebar,
    MobileMenu
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

      // document.body.style.zoom = "90%";
    },

  },
  computed: {
    layout: function () {
      return this.$route.meta.layout;
    },
    statusSidebar: function (){
      return this.$store.state.sidebar;
    },
    menu: function () {
      return this.$store.state.menu;
    },
    getUsername: function() {
      return this.$store.state.user.data.name +" "+ this.$store.state.user.data.lastname

    }
  },
  mounted: function() {
    window.addEventListener('resize', this.handleResize);
    this.handleResize();
    this.$store.dispatch('getUserName');

  },
}
</script>
