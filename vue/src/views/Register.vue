<style src="vue-multiselect/dist/vue-multiselect.css"/>

<style>

.multiselect__single {
    padding-top: 5px !important;
}

.multiselect__placeholder {
  display: inline-block !important;
  margin-bottom: 0px !important;
  padding-top: 0px !important;
}


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
width: 100% !important;
font-family: inherit !important;
font-weight: normal !important;
color: none !important;
border: none !important;
height: 100% !important;
/* font-size: 11px !important; */
line-height: normal! important;
}

.multiselect__placeholder{
  /* margin-left: 10px;
  margin-top: 2px; */
  font-size: 16px !important;
}

.multiselect__tag {
  background: #f0f0f0 !important;
  border: 1px solid rgba(60, 60, 60, 0.26) !important;
  /* color: black !important; */
  margin-bottom: 0px !important;
  margin-right: 5px !important;
}

</style>
<template>
  <div class="flex flex-col w-[450px] sm:w-full text-base">
    <span class="text-center sm:text-left text-[28px] leading-9 mb-10 sm:mb-8">¡Registrate gratis!</span>

    <form class="mt-full space-y-6" @submit="register">

      <Alert
        v-if="Object.keys(errors).length"
        class="flex-col items-stretch text-sm"
      >
        <div v-for="(field, i) of Object.keys(errors)" :key="i">
          <div v-for="(error, ind) of errors[field] || []" :key="ind">
            * {{ error }}
          </div>
        </div>
      </Alert>

      <input type="hidden" name="remember" value="true" />

        <TInput
          :id="'name'"
          name="name"
          v-model="user.name"
          :errors="errors"
          class="h-[62px]"
          placeholder="Ingresa tu nombre"
          ></TInput>

          <TInput
          :id="'lastname'"
          name="lastname"
          v-model="user.lastname"
          :errors="errors"
          class="h-[62px]"
          placeholder="Ingresa tu apellido"></TInput>

          <TInput
          :id="'celular'"
          type="text"
          @keypress="onlyNumber"
          name="celular"
          v-model="user.celular"
          :errors="errors"
          placeholder="Numero de Celular"
          class="h-[62px]"></TInput>


          <!-- <TInput
          name="nombreempresa"
          v-model="user.nombreempresa"
          :errors="errors"
          placeholder="Nombre de la empresa"

          class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-4"></TInput> -->

    <div class="h-[52px] border border-[#8A9CC9] rounded ">
    <VueMultiselect
            ref="multiselect"
            v-model="value"
            :options="options"
            track-by="id"
            label="name"
            :custom-label="nameFormatter"
            ::multiple="false"
            @select="obtenemosvalor"
            placeholder="Busca tu empresa"
            @search-change="loadSuggestions"

            :show-no-results="true"
            :max-height="300"
            :limit-text="limitesResultados"
            :options-limit="50"
            :limit="3"
            open-direction="bottom"
            :style="'font-size:100%; height: 100%;'"
            :showNoOptions="false"

            :prevent-autofocus="true"
            :disabled="desabilitarEmpresa"






            >

          <template #noResult>

            <div @click="eventoparaagregar" class="cursor-pointer">  + ¿ Agregar Nueva Empresa ? </div>
          </template>

    </VueMultiselect>
  </div>
    <!-- <nav>{{user}}</nav> -->

        <TInput
          :id="'email'"
          type="email"
          name="email"
          v-model="user.email"
          :errors="errors"
          @input="tecla_apretada('email')"
          placeholder="Correo empresarial"
          class="h-[62px]"></TInput>





          <!-- <select
          v-model="user.cargo"
          :errors="errors"
          class="w-full h-[52px] border border-[#8A9CC9] rounded px-4 mb-4"

          >
          <option disabled value="">Seleccionar un cargo</option>
          <option
          v-for="item in cargos" v-bind:key="item.codCargo" v-bind:value = "item.codCargo">
            {{ item.nameCargo }}
          </option>

          </select> -->

        <TInput
          :id="'password'"
          type="password"
          name="password"
          v-model="user.password"
          :errors="errors"
          placeholder="Contraseña"

          class="h-[62px]"></TInput>

        <TInput
          :id="'password_confirmation'"
          type="password"
          name="password_confirmation"
          v-model="user.password_confirmation"
          :errors="errors"
          placeholder="Confirmación contraseña"

          class="h-[62px]"></TInput>







      <div class="flex flex-col w-[450px] sm:w-full text-base">
        <TButtonLoading
          :loading="loading"
          class="bg-orange rounded h-14 w-full text-white text-center leading-9 sm:w-full sm:mb-4"
        >
          Registrarse
        </TButtonLoading>
      </div>
    </form>

  </div>

  <NewCompany
  v-if="modalName === 'newproject'"
  @closeModal   ="closeModal"
  @crearEmpresa = "datosProyecto"
  v-model="datapasar"
  />

</template>


<script>
import VueMultiselect from 'vue-multiselect'
import store from "../store";
import { ref } from "vue";
import TButtonLoading from "../components/core/TButtonLoading.vue";
import TInput from "../components/core/TInput.vue";
import Alert from "../components/core/Alert.vue";
import NewCompany from '../components/NewCompany.vue'
import { encrypt } from '../cryptoUtil';

export default {
   name: "Register",
   components: {
    // LockClosedIcon,
    Alert,
    TButtonLoading,
    TInput,
    VueMultiselect,
    ref,
    NewCompany
   },
   data() {
     return {

       value    :  null,
       loading  :  false,
       isLoading: false,
       errors : {},
       user : {
          email: "",
          password: "",
       },
       options: [],
       user : {
                name: "",
                lastname:"",
                celular: "",
                nombreempresa:"",
                codempresa: null,
                email: "",
                password: "",
                cargo:""
              },
       modalName : "",
       desabilitarEmpresa: false
     };
   },
   methods:{

    tecla_apretada: function(name){
         console.log(this.errors[name] + ">>> entrrando")
        if (this.errors[name] != undefined){
            delete this.errors[name];
        }

      },
      closeModal: function () {
        // this.searchText = ""
        // this.business   = ""
        // if (this.modalName === "") this.$store.commit("increaseHint");
         this.modalName = "";
      },
      datosProyecto: function (datos) {

      this.$store.dispatch('save_newempresa', datos)
          .then((response) => {

            this.modalName      = "";
            this.suggestiondata = [];

            if (response["flag"] == 1){

              // this.business   = response["registro"]["cod_Empresa"];
              // this.searchText = response["registro"]["des_Empresa"];
              this.value                = {id:response["registro"]["cod_Empresa"] , name:response["registro"]["des_Empresa"]}
              this.user.codempresa      =  response["registro"]["cod_Empresa"];
              this.user.nombreempresa      =  response["registro"]["des_Empresa"];
              this.desabilitarEmpresa   = false;

            }

      });

      },

      limitesResultados (count) {
        return `y ${count} Empresas mas.`
      },
      obtenemosvalor({id,name}){
        this.user.codempresa = id;
        this.user.nombreempresa = name;
      },
      eventoparaagregar(){

        // this.internalSearch = true
        this.$refs.multiselect.search = '';
        this.desabilitarEmpresa   = true;
        this.modalName      = 'newproject';
        this.value          = null;
        // console.log(">>>>>>>")
      },
      nameFormatter({id, name}){
        return `${name}`;
      },
      onlyNumber ($event) {

          let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
          if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
              $event.preventDefault();
          }
      },
      clearSuggestions () {
        this.selectedCountries = []
      },
      loadSuggestions: function(data){

        var el              = this;
				this.suggestiondata = [];

        this.isLoading = true

				// if(data != ''){

          var enviamos = { buscar : data }
          this.$store.dispatch('get_buscar', enviamos)
          .then((response) => {
            el.options  = []
            //console.log(response)
            for (let index = 0; index < response.length; index++) {
              el.options.push({id:response[index]["cod_Empresa"], name: response[index]["des_Empresa"]})
            }
              // el.options.push({id:-999, name: '+ Agregar Nueva Empresa'})
              this.isLoading = false
            // el.suggestiondata  = data
          })

				// }

      },

      // loadSuggestions: function(e){
			// 	var el = this;
			// 	this.suggestiondata = [];

			// 	if(this.searchText != ''){
      //     var enviamos = { buscar : this.searchText }
      //     this.$store.dispatch('get_buscar', enviamos)
      //     .then((response) => {
      //       let data = []
      //       //console.log(response)
      //       for (let index = 0; index < response.length; index++) {
      //         data.push({cod_Empresa:response[index]["cod_Empresa"], des_Empresa: response[index]["des_Empresa"]})
      //       }
      //       data.push({cod_Empresa:-999, des_Empresa: '+ Agregar Nueva Empresa'})
      //       el.suggestiondata  = data
      //     })

			// 	}

			// },


      register(ev) {
        ev.preventDefault();
        this.loading = true;
        store
          .dispatch("register", this.user)
          .then(() => {


            const data = {
                  msg: 'Usuario Registrado Correctamente !',
            };

            const datosEncriptados = encrypt(JSON.stringify(data));
            this.loading = false;
            this.$router.push({
                  name: 'login',
                  query: { msg: datosEncriptados }
            });

          })
          .catch((error) => {
            this.loading = false;
            if (error.response.status === 422) {
              this.errors = error.response.data.errors;
            }
          });
      }

   },
  //  mounted() {
  //   console.log("entrando al mounted")
  //   this.value = this.options.find(option => option.id === this.user.codempresa);

  //  },

  }

</script>
