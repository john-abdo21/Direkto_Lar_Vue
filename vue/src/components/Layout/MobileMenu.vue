<template>
  <div class="hidden relative sm:flex h-screen bg-white w-full pt-20">
    <ul class="w-full">
      <li class="text-xl cursor-pointer py-6">
        <a
          class="flex items-center justify-between px-8 mb-3"
          @click="handleClick('operation')"
        >
          <span>Operaciones</span>
          <img
            src="../../assets/arrow-up.svg"
            class="justify-items-center flex transition"
            :class="{ 'rotate-180': operation, 'rotate-0': !operation }"
            alt=""
          />
        </a>
        <ul v-if="operation">
          <li
            v-for="route in routes"
            :key="route.id"

            class="cursor-pointer relative py-3 text-base px-8"
            :class="{
              'bg-[#002b6b]':route.cerrar,
              'text-white':  route.cerrar,
              'z-30 bg-white': (hint === 2 && route.id === 'start') || (hint === 3 && route.id === 'execution')}"
          >
            <a
              class="flex items-center justify-between"
              @click="handleClick(route.id) "
            >
            <span class="flex items-center">
                <img
                  v-if="route.img"
                  :src="path+'/' + route.img + '.svg'"
                  v-bind:alt="route.img "

                />
                <span class="px-2">{{ route.label }}</span>
              </span>
              <img
                src="../../assets/arrow-up.svg"
                class="justify-items-center flex transition"
                :class="{
                  'rotate-180': route.isOpen,
                  'rotate-0': !route.isOpen,
                }"
                alt=""
              />
            </a>
            <ul v-if="route.childs && route.isOpen" class="list-disc px-6">
              <li
                v-for="child in route.childs"
                :key="child.id"
                class="cursor-pointer mt-6"
                @click="handleRedirect(child.path)"
              >
                {{ child.label }}
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <div class="w-full px-8">
        <hr class="h-[1px] px-8 border-[#D0D9F1]" />
      </div>
      <li class="text-xl cursor-pointer py-6 px-8">
        <a
          class="flex items-center justify-between"
          @click="handleClick('administration')"
        >
          <span>Administración</span>
          <img
            src="../../assets/arrow-up.svg"
            class="justify-items-center flex transition"
            :class="{
              'rotate-180': administration,
              'rotate-0': !administration,
            }"
            alt=""
          />
        </a>
      </li>
      <div class="w-full px-8">
        <hr class="h-[1px] px-8 border-[#D0D9F1]" />
      </div>
      <li class="text-xl cursor-pointer py-6 px-8">
        <a
          class="flex items-center justify-between"
          @click="handleClick('security')"
        >
          <span>Seguridad</span>
          <img
            src="../../assets/arrow-up.svg"
            class="justify-items-center flex transition"
            :class="{ 'rotate-180': security, 'rotate-0': !security }"
            alt=""
          />
        </a>
      </li>
      <div class="w-full px-8">
        <hr class="h-[1px] px-8 border-[#D0D9F1]" />
      </div>
      <li class="text-xl cursor-pointer py-6 px-8">
        <a
          class="flex items-center justify-between"
          @click="handleClick('quality')"
        >
          <span>Calidad</span>
          <img
            src="../../assets/arrow-up.svg"
            class="justify-items-center flex transition"
            :class="{ 'rotate-180': quality, 'rotate-0': !quality }"
            alt=""
          />
        </a>
      </li>
      <div class="w-full px-8">
        <hr class="h-[1px] px-8 border-[#D0D9F1]" />
      </div>
      <li class="text-xl cursor-pointer py-6 px-8">
        <a
          class="flex items-center justify-between"
          @click="handleClick('logistic')"
        >
          <span>Logística</span>
          <img
            src="../../assets/arrow-up.svg"
            class="justify-items-center flex transition"
            :class="{ 'rotate-180': logistic, 'rotate-0': !logistic }"
            alt=""
          />
        </a>
      </li>
    </ul>
    <Hint
      :header="'Encuentra todos tus proyectos'"
      :paragraph="'Cuando los crees, aquí podrás encontrarlos junto a todo su detalle'"
      :next="'Siguiente'"
      :icon="true"
      :bubbleCss="'before:bottom-full before:border-[9px] before:left-1/2 before:-translate-x-1/2 before:border-transparent	before:border-b-white before:-mb-[1px]'"
      :positionCss="'top-64 left-1/2 -translate-x-1/2'"
      v-if="hint === 2 && projectLength === 0"
    />
    <Hint
      :header="'Proyectos en ejecución'"
      :paragraph="'Revisa el análisis de restricciones y control de obras de tus proyectos.'"
      :next="'Finalizar'"
      :icon="false"
      :bubbleCss="'before:bottom-full before:border-[9px] before:left-1/2 before:-translate-x-1/2 before:border-transparent	before:border-b-white before:-mb-[1px]'"
      :positionCss="'top-[352px] left-1/2 -translate-x-1/2'"
      v-if="hint === 3 && projectLength === 0"
    />
  </div>
</template>

<script>
import Hint from '../Hint.vue';
export default {
  name: "mobile-menu-component",
  components: {
    Hint,
  },
  data: function () {
    return {
      path : import.meta.env.VITE_WEB_FIN_BASE_URL,
      operation: false,
      administration: false,
      security: false,
      quality: false,
      logistic: false,
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
              cerrar: false
            },
            {
              id: "graphic_advance",
              label: "Avance gráfico",
              path: "/graphic_advance",
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
          label: "Cierre",
          path: "/closing",
          cerrar: true,
          img: "close"
        },
      ],
    };
  },
  methods: {

    logout: function (param) {
      store.dispatch("logout").then(() => {
        router.push({
          name: "login",
        });
      });
    },
    handleClick: function (param) {
      console.log(">>>>>> lllegamosooo")
      console.log(param)
      switch (param) {
        case "operation":
          this.operation = !this.operation;
          break;
        case "administration":
          this.administration = !this.administration;
          break;
        case "security":
          this.security = !this.security;
          break;
        case "quality":
          this.quality = !this.quality;
          break;
        case "logistic":
          this.logistic = !this.logistic;
          break;
        default:
          this.routes.map((item) => {
            if(param == 'closing'){

              this.logout();

            }else{

              if (item.id === param) {
              item.isOpen = !item.isOpen;
            }

            }

          });
          break;
      }
    },
    handleRedirect(path) {
      this.$store.commit('toggleMenu');
      this.$router.push(path);
    },
    openSide: function() {
      this.operation = true;
      const curHint = this.$store.state.hint;
      if (curHint === 2) {
        this.routes[0].isOpen = true;
        this.routes[1].isOpen = false;
      } else if (curHint === 3) {
        this.routes[0].isOpen = false;
        this.routes[1].isOpen = true;
      }
    }
  },
  computed: {
    hint: function() {
      return this.$store.state.hint;
    },
    projectLength: function() {
      return this.$store.state.project_rows.length;
    },
  },
  mounted: function() {
    this.openSide();
  },
  updated: function() {
    this.openSide();
  }
};
</script>
