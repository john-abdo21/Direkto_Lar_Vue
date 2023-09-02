<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style>
.multiselect__placeholder {
  display: inline-block !important;
  margin-bottom: 0px !important;
  padding-top: 0px !important;
}

/* .multiselect.invalid .multiselect__tags {
  border: 1px solid #f86c6b !important;
} */

.multiselect__element {
  /* background: #428bca !important; */
  font-family: inherit !important;
font-weight: normal !important;
color: none !important;
/* font-size: 11px !important; */
line-height: normal! important;
}

.multiselect__option--highlight {
  background: #428bca !important;
  font-family: inherit !important;
font-weight: normal !important;
color: none !important;
/* font-size: 11px !important; */
line-height: normal! important;
}

.multiselect__option--highlight:after {
  background: #428bca !important;
}

.multiselect__tags {
  /* padding: 5px !important;
  min-height: 10px;
} */
font-family: inherit !important;
font-weight: normal !important;
color: none !important;
/* font-size: 11px !important; */
line-height: normal! important;
}
/* .multiselect__single{
  font-family: inherit !important;
} */
.multiselect__placeholder{
  margin-left: 10px;
  margin-top: 2px;
}

.multiselect__tag {
  background: #f0f0f0 !important;
  border: 1px solid rgba(60, 60, 60, 0.26) !important;
  /* color: black !important; */
  margin-bottom: 0px !important;
  margin-right: 5px !important;
}

/* .multiselect__tag-icon:after {
  color: rgba(60, 60, 60, 0.5) !important;
}

.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
  background: #f0f0f0 !important;
}

.multiselect__tag-icon:focus:after,
.multiselect__tag-icon:hover:after {
  color: red !important;
} */

/* .multiselect-single{
  font-family: inherit;
} */
/* .multiselect__tag{

} */
</style>

<template>
  <div class="flex flex-col w-[450px] sm:w-full text-base">
    <span class="text-center sm:text-left text-[28px] leading-5 mb-8 sm:mb-8"> Iniciar Sesión</span>
    <nav class=" text-center text-sm text-gray-600">
      O
      {{ " " }}
      <router-link
        :to="{ name: 'registro' }"
        class="font-medium text-indigo-600 hover:text-indigo-500"
      >
        Registrate
      </router-link>
    </nav>
  <span class="text-center sm:text-left text-[16px] leading-9 mb-10 sm:mb-8"> Indicanos tu correo y contraseña</span>


  <form class="mt-8 space-y-6" @submit="login">
    <Alert v-if="errorMsg">
      {{ errorMsg }}
      <span
        @click="errorMsg = ''"
        class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </span>
    </Alert>
    <input type="hidden" name="remember" value="true" />
    <div class="rounded-md shadow-sm -space-y-px">
      <div>
        <label for="email-address" class="sr-only">Email address</label>
        <input
          id="email-address"
          name="email"
          type="email"
          autocomplete=""
          required=""
          v-model="user.email"
          class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          placeholder="Correo"
        />
      </div>
      <div>
        <label for="password" class="sr-only">Password</label>
        <input
          id="password"
          name="password"
          type="password"
          autocomplete=""
          required=""
          v-model="user.password"
          class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          placeholder="Contraseña"
        />
      </div>
    </div>
<!--
    <VueMultiselect
            v-model="value"
            :options="options"
            track-by="name"
            label="name"
            :custom-label="nameFormatter"
            ::multiple="false"
            @search-change="obtenemosevento"
            placeholder="Seleccionar"



            >

      <template #noResult>
        Lo siento no tenemos resultados.
        <div @click="eventoparaagregar">  Quieres agregar una empresa +</div>
     </template>

    </VueMultiselect> -->

    <nav>{{value}}</nav>

    <!-- <div class="flex items-center justify-between">
      <div class="flex items-center">
        <input
          id="remember-me"
          name="remember-me"
          type="checkbox"
          v-model="user.remember"
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
        />
        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
          Remember me
        </label>
      </div>
    </div> -->

    <div>
      <TButtonLoading :loading="loading" class="w-full relative justify-center h-14 leading-8  sm:w-full sm:mb-4 rounded  bg-orange">
        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
          <!-- <LockClosedIcon
            class="h-5 w-5 text-white-500 group-hover:text-indigo-400"
            aria-hidden="true"
          /> -->
        </span>
        Ingresar
      </TButtonLoading>
    </div>
  </form>

  </div>


</template>

<!-- <template>

  <form  @submit="login">
    <Alert v-if="errorMsg">
      {{ errorMsg }}
      <span
        @click="errorMsg = ''"
        class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </span>
    </Alert>
  <div class="flex flex-col w-[450px] sm:w-full text-base">
    <span class="text-center sm:text-left text-[28px] leading-9 mb-10 sm:mb-8"> Iniciar Sesión</span>
    <span class="text-center sm:text-left text-[16px] leading-9 mb-10 sm:mb-8"> Indicanos tu correo y contraseña</span>
    <input  id="email-address"
          name="email"
          type="email"
          autocomplete="email"
          required=""
          placeholder="Email"
          v-model="user.email" class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-4">
    <input id="password"
          name="password"
          type="password"
          autocomplete="current-password"
          placeholder="Contraseña"
          required="" class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-4">
    <div class="flex justify-between sm:flex-col sm:flex-col-reverse">
      <button class="bg-orange rounded h-14 w-[212px] text-white leading-4 sm:w-full sm:mb-4" @click="next">Ingresar</button>
    </div>
  </div>
 </form>
</template>
 -->


 <script>
//  import draggable from "vuedraggable";
//  import Vue from 'vue';
//  import { LockClosedIcon } from "@vue-hero-icons/solid"
//  import VueMultiselect from 'vue-multiselect'
 import Alert from "../components/core/Alert.vue";
 import TButtonLoading from "../components/core/TButtonLoading.vue";
 import { ref } from "vue";
 import store from "../store";

 export default {
   name: "Login",
   components: {
    // LockClosedIcon,
    Alert,
    TButtonLoading,
    // VueMultiselect,
    ref
   },
   data() {
     return {

      //  options  :  [{id:1 , name: 'veremo'},{id:2, name:" no tenemos "}],
      //  value    :  null,
       loading  :  false,
       errorMsg : "",
       user : {
          email: "",
          password: "",
       },
      //  options: [
      //    { id: 1, name: "Abby", sport: "basket" },
      //    { id: 2, name: "Brooke", sport: "foot" },
      //    { id: 3, name: "Courtenay", sport: "volley" },
      //    { id: 4, name: "David", sport: "rugby" }
      //  ],
       dragging: false
     };
   },
   methods:{
    // siactualizamos()
    // {},
    // eventoparaagregar(){
    //   this.options.push({id:5, name:"Diego lo hizo", sport:"asasasa"})
    // },
    // obtenemosevento(){
    //   console.log(">>>>> obtenemos evento de cambio"+this.value)
    // },
    // nameFormatter({id, name}){
    //   return `${name}`;
    // },
    login(ev) {
            ev.preventDefault();

            this.loading = true;
            store
              .dispatch("login", this.user)
              .then(res => {
                // console.log(">>>>>>> entramos aqui ");
                // console.log(res)
                this.loading = false;
                //router.getRoutes()
                this.$router.push({
                 name: 'Home'
                });

              }).catch((err) => {
                console.log(err);
                this.loading = false;
                this.errorMsg = err.response.data.error;
              });
    }

   },
   computed: {

   }
 };
 </script>
