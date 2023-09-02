<template>
  <div class="relative">
    <div
      class="flex shrink-0 flex-col justify-between top-0 left-0 pt-[82px] h-screen bg-side sm:hidden transition-all duration-300"
      :class="openSidebar === true ? 'w-48' : 'w-0 overflow-hidden'"
    >
      <div>
        <h1 class="text-sm mb-6 px-5 font-bold">Tus aplicaciones</h1>
        <ul>
          <li
            v-for="route in routes"
            :key="route.id"
            class="cursor-pointer py-3 relative pl-4 pr-5"
            :class="{
              'bg-[#002b6b]':route.cerrar,
              'text-white':route.cerrar,
              'z-30 bg-side':
                (hint === 2 && route.id === 'start') ||
                (hint === 3 && route.id === 'execution'),
            }"

          >
            <a
              class="flex items-center justify-between"
              @click="handleClick(route.id)"
            >
              <span class="flex items-center">
                <img
                  v-if="route.img"
                  :src="path+'/' + route.img + '.svg'"
                  v-bind:alt="route.img "

                />
                <span class="px-2 text-xs font-bold">{{ route.label }}</span>
              </span>
              <img
                src="../../assets/arrow-up.svg"
                class="justify-items-center flex transition"
                :class="{
                  'rotate-180': route.isOpen,
                  'rotate-0': !route.isOpen,
                }"
                v-if="route.childs"
                alt=""
              />
            </a>
            <ul
              class="border-l border-l-[#8A9CC9] ml-4 mt-4"
              v-if="route.childs && route.isOpen"
            >
              <li
                v-for="child in route.childs"
                :key="child.id"
                class="font-medium text-[0.65rem] leading-5 cursor-pointer pl-4 mb-3 last:mb-0 -ml-0.5"
                :class="{
                  'text-activeText before:border-l-[2px] before:border-l-[#EB5D00] before:absolute before:-ml-[15.5px] before:h-4 before:rounded':
                    child.path === currentPath,
                  'text-inactiveText': child.path !== currentPath,
                }"
                @click="handleRedirect(child.path)"
                :title="child.title"
              >
                {{ child.label }}
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div
        class="relative cursor-pointer flex items-center justify-end py-5 text-xs px-8 bottom-0 h-[30px] border-t border-t-[#D0D9F1]"
        @click="$emit('toggleSidebar')"
      >
        <img
          src="../../assets/arrow-left.svg"
          alt=""
          class="justify-items-center"
        />
        <span class="text-[0.7rem] font-bold  text-[#002B6B]">Ocultar</span>
      </div>
    </div>
    <div
      class="absolute -right-10 h-10 w-10 bottom-0 flex bg-side rounded-r sm:hidden"
      :class="openSidebar === false ? '' : 'hidden'"
      @click="$emit('toggleSidebar')"
    >
      <img
        src="../../assets/arrow-left.svg"
        alt=""
        class="rotate-180"
      />
    </div>
  </div>
</template>

<script>
import store from "../../store"
export default {
  name: "sidebar-component",
  props: {
    openSidebar: Boolean,
  },
  data: function () {
    return {
      path : import.meta.env.VITE_WEB_FIN_BASE_URL,
      routes: [
        {
          id: "start",
          label: "Inicio",
          path: "/start",
          img: "home",
          isOpen: false,
          childs: [
            {
              id: "create_project",
              label: "Tus proyectos",
              path: "/proyectos",
              title: "Administra y crea tus proyectos",
              cerrar: false
            },
          ],
        },
        {
          id: "execution",
          label: "Ejecución",
          path: "/execution",
          img: "execution",
          isOpen: false,
          childs: [
            {
              id: "restrictions_analysis",
              label: "Analisis de restricciones",
              path: "/restricciones",
              title: "Administra el analisis de restricciones, crea restricciones , genera reportes y envia alertas.",
              cerrar: false
            },
            {
              id: "graphic_advance",
              label: "Avance gráfico",
              path: "/graphic_advance",
              title: "Pagina en construcción",
              cerrar: false
            },
          ],
        },
        {
          id: "planning",
          label: "Planificación",
          path: "/planning",
          cerrar: false
        },
        {
          id: "control",
          label: "Control",
          path: "/control",
          cerrar: false
        },
        {
          id: "closing",
          label: "Cerrar",
          path: "/closing",
          img: "close",
          cerrar: true
        },
      ],
    };
  },
  methods: {
    logout: function (param) {
      store.dispatch("logout").then(() => {
        this.$router.push({
          name: "login",
        });
      });
    },


    handleRedirect(path) {
      this.$router.push(path);
    },
    handleClick(id) {
      // console.log(">>> verificamossss ")
      // console.log(this.routes)
      // console.log(id)
      if(id  == 'closing')  {
        this.logout();
      }

      this.routes.map((item) => {

          if (item.id == id) {
          item.isOpen = !item.isOpen;
          }


      });
    },
    openSide: function () {
      const curHint = this.$store.state.hint;
      curHint < 4 && (this.routes[0].isOpen = true);
      curHint === 3 && (this.routes[1].isOpen = true);
    },
  },
  computed: {
    currentPath() {
      return this.$route.path;
    },
    hint: function () {
      return this.$store.state.hint;
    },
  },
  mounted: function () {
    this.openSide();
    console.log(">>> validamos el estado del sidebar ")
    console.log(this.openSidebar)
  },
  updated: function () {
    this.openSide();
  },
};
</script>
