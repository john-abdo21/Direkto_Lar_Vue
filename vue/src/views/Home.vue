<template>
  <Breadcrumb
  :paths="['Inicio']"
   />
  <div>Bienvenidos</div>
  <slot></slot>

  <ConfirmMultiple
        v-if="modalName === 'ConfirmMultiple'"
        :confirmHeader="''"
        :header="'Invitaciones a Proyectos'"
        :paragraphs="[
          'Usted tiene actualmente las siguientes invitaciones confirme o rechace las invitaciones',
        ]"
        :infoProyectos = "infoProyectos"

        @closeModal="closeModal"
        @confirmChanges="confirmChanges"
  />
</template>

<script>

import ConfirmMultiple from "../components/ConfirmMultiple.vue";
import store from "../store";
import Breadcrumb from "../components/Layout/Breadcrumb.vue";
export default {
  name: "white-project-component",
  components: {
    Breadcrumb,
    ConfirmMultiple
  },
  data: function () {
    return {
              modalName: "",
              infoProyectos: [],
              firstLogueo  : 0
           }

  },
  methods: {
    handleRedirect(path) {
                this.$router.push(path);
    },
    openModal: function (param) {
      this.modalName = param;
    },
    closeModal: function () {
      if (this.modalName === "") this.$store.commit("increaseHint");
      else this.modalName = "";
    },

    confirmChanges: function (info) {


      store.dispatch('update_projects_without_approve', info).then(res => {
        console.log(res)
        if(res.data.estado == true){

          this.closeModal();
          this.handleRedirect('proyectos')
          //  this.infoProyectos = res.data.datos
        }

      });

    },
    getPendings : async function () {
      await store.dispatch('get_projects_without_approve').then(res => {
        if(res.data.estado == true){
           this.infoProyectos = res.data.datos
        }

      });

    },



  },
  mounted:  async function() {

  //  store.dispatch('get_infoPerson');
  this.firstLogueo  = this.$store.state.user.firstLogin;
  await this.getPendings();

  if(this.infoProyectos.length > 0 ){
      this.modalName = 'ConfirmMultiple'
      console.log(">>> entramos ConfirmMultiple")
   }

  //   console.log(">>> comenzaoms el mounted")
  // console.log(this.infoProyectos)



  // console.log(">>> comenzaoms el mounted")
  // console.log(this.infoProyectos)
  //  if(this.firstLogueo == 1 && this.proyectoPendientes.length > 0 ){
  //   this.modalName = 'ConfirmMultiple'
  //   console.log(">>> entramos ConfirmMultiple")
  //  }

  },
  computed: {
    // proyectoPendientes: function() {
    //   return this.infoProyectos
    // },

    // primerLogueo: function(){

    //   return this.$store.state.user.firstLogin;

    // }
  }
}



// import { computed, ref } from "vue";
// import { useRoute } from "vue-router";
// import { useStore } from "vuex";

// const route = useRoute();
// const store = useStore();
// if (window.localStorage) {

//   if (!localStorage.getItem('reload')) {
//       localStorage['reload'] = true;
//       window.location.reload();
//   } else {
//       localStorage.removeItem('reload');
//   }
// }
/* store.dispatch('get_project')
.catch((error) => {
  console.log(error)
}); */
</script>
