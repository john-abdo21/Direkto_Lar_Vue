<template>

  <div class="flex flex-col w-[80%] m-auto text-[0.7rem]">
    <span @click="probar" class="text-xl mb-4"
      >Asigna los miembros de tu primer proyecto
    </span>
    <div class="flex justify-between sm:flex-col mb-8">
      <div class="flex flex-col w-[6%] sm:w-full sm:mb-8">
        <span class="text-xs leading-6 mb-4">Acción</span>

        <div
              class="h-[32px] w-full mb-4  rounded px-4"
              v-for="(user, index) in users"
              :key="index"
        >

          <button
            @click="eliminarUsuario(user.codProyIntegrante)"
            class="p-2 rounded-full bg-red-500 text-white w-[2.5em]"


        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>

        </div>


      </div>

      <div class="flex flex-col w-[30%] sm:w-full sm:mb-8">
        <span class="text-xs leading-6 mb-4">Ingresa el correo del miembro</span>
        <div
            class="autocompleteel h-[32px] w-full mb-4 border border-[#8A9CC9] rounded rounded flex items-center justify-center"
            :class="{ 'invalid-input': (user.error_userEmail != undefined)  }"
            v-for="(user, index) in users" v-bind:key="user.id" >

            <input type="hidden" v-model="user.id">
            <input
              type="text"
              :disabled="user.flgInsertado"
              :key="index"
              placeholder="Correo electrónico"
              v-model="user.userEmail"
              class="w-full pt-1.5 pb-1.5 rounded px-4"
              @keyup='loadSuggestions(user.userEmail, index);'
              @focus="limpiarErrores()"
            />
            <br>
                <div class="w-[110%] mx-[-5%] rounded bg-white border border-gray-300 px-4 py-2 space-y-1 relative z-50"
                v-if="user.suggestiondata.length > 0 "
                >
                <ul>
                    <li
                    class="px-1 pt-1 pb-2 font-bold border-b border-gray-200"
                    >
                    Mostrando {{ user.suggestiondata.length }} resultados
                    </li>
                    <li
                    v-for= 'item in user.suggestiondata'
                    v-bind:key="item.id"
                    v-bind:value = "item.email"

                    @click="user.userEmail = item.email; user.id = item.id; user.suggestiondata = [];"
                    class="cursor-pointer hover:bg-gray-100 p-1"
                    >
                      {{ item.email }}
                    </li>
                </ul>
                </div>

        </div>

      </div>
      <div class="flex flex-col w-[30%] sm:w-full sm:mb-8">
        <span class="text-xs leading-6 mb-4">Selecciona el rol</span>

        <select
        v-model="value.userRole"
        v-for="(value, index) in users"
        :key="index"
        class="h-[32px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
        :class="{ 'invalid-input': (value.error_userRole != undefined)  }"
        @focus="limpiarErrores()"
        >
          <option
          v-for="item in rolIntegrantes" v-bind:key="item.value" v-bind:value = "item.value" :selected="value.userRole" >
            {{ item.name }}
          </option>

        </select>

      </div>
      <div class="flex flex-col w-[30%] sm:w-full">
        <span class="text-xs leading-6 mb-4"
          >Selecciona área al que pertenece</span
        >

        <select
        v-model="value.userArea"
        v-for="(value, index) in users"
        :key="index"
        class="h-[32px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
        :class="{ 'invalid-input': (value.error_userArea != undefined)  }"
        @focus="limpiarErrores()"
        >
          <option
          v-for="item in areaIntegrantes" v-bind:key="item.value" v-bind:value = "item.value" :selected="value.userArea" >
            {{ item.name }}
          </option>

        </select>

      </div>
    </div>
    <div class="flex cursor-pointer mb-8" >
      <img
        src="../assets/tooltip-person-add-active.svg"
        class="mr-2"
        alt=""
      />
      <span class="text-sm leading-4 text-orange" @click="addUser">Agregar miembro</span>
    </div>

    <span class="text-red-500 flex items-start" v-if="!validarMiembros()">
        <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938-1.463A9.986 9.986 0 0011 19.103V22h2v-2.897a9.986 9.986 0 005.938-9.54l-.698-.107L17.246 5H6.754l-.694 3.456-.698.107z"/>
        </svg>
        <span>El proyecto necesita que agregues miembros.</span>
    </span>

  </div>
</template>

<script>
import Select from "./Select.vue";
export default {
  name: "second-step-component",
  components: {
    Select,
  },
  data: function () {
    return {
      flgNotenemosmiembros:false,
      correlative : -999,
      areaIntegrantes : [],
      rolIntegrantes  : [],
      suggestiondata:[],
      users: [],
      search: ""
    };
  },
  methods: {

    limpiarErrores : function (){

      this.users.forEach((user,index) => {

        try {

           delete this.users[index]['error_userEmail'];
           delete this.users[index]['error_userRole'];
           delete this.users[index]['error_userArea'];

        } catch (error) {

        }



      });

    },
    validarMiembros : function (){
      return this.users.length > 0 ? true : false
    },
    validarCampos : function () {

      // console.log(this.users)
      let conteoErrores = 0
      this.users.forEach((user,index) => {
        // console.log(">>> dataaaa ")
        // console.log(user)
        // console.log(index)
        if(user['userEmail'].trim() == ''){
          this.users[index]['error_userEmail'] = 'Llenar el campo de correo de usuario'
          conteoErrores++;
        }
        if(user['userRole'] == ''){
          this.users[index]['error_userRole'] = 'Elige el rol del usuario'
          conteoErrores++;
        }
        if(user['userArea'] == ''){
          this.users[index]['error_userArea'] = 'Elige el area del usuario'
          conteoErrores++;
        }
      });

      console.log(">> impresion de la data de users <<")
      console.log(this.users)

      return conteoErrores;
    },
    eliminarUsuario : function (codProyIntegrante) {

      this.users = this.users.filter(item => item.codProyIntegrante !== codProyIntegrante);

    },
    probar : function () {
      console.log(this.users)
    },
    handleClick: function (param) {
      if (param === "coveredArea")
        this.coveredAreaStatus = !this.coveredAreaStatus;
      else if (param === "projectType")
        this.projectTypeStatus = !this.projectTypeStatus;
      else if (param === "district") this.districtStatus = !this.districtStatus;
    },
    addUser: function () {
      this.correlative  = this.correlative + 1
      var temp = {
        codProyIntegrante : this.correlative ,
        userEmail: "",
        userRole: "",
        userArea: "",
        flgInsertado: false,
        isNew:true,
        suggestiondata : []
      };
      // console.log("as")
      this.users.push(temp);

    },
    selRole: function(payload) {
      this.users[payload.indexVal].userRole = payload.value;
      this.paraStatus = false;
    },
    selArea: function(payload) {
      this.users[payload.indexVal].userArea = payload.value;
      this.paraStatus = false;
    },
    loadSuggestions: function(buscar, index){
				var el = this;
				el.users[index].suggestiondata = [];

				if(this.searchText != ''){
          var enviamos = { buscar : buscar }
          this.$store.dispatch('get_buscar_usuarios', enviamos)
          .then((response) => {
            let data = []
            //console.log(response)
            for (let index = 0; index < response.length; index++) {
              data.push({id:response[index]["id"], email: response[index]["email"]})
            }
            // data.push({id:-999, des_Empresa: '+ Agregar Nueva Empresa'})
            //el.suggestiondata  = data
            el.users[index].suggestiondata = data
          })

				}

			},
      itemSelected: function(campo , valor){
          campo   = valor

      }
  },
  computed: {


  },
  beforeMount: function() {


  },
  beforeCreate() {


  },

};
</script>

<style>
  .invalid-input {
    border-color: red !important;
  }
</style>
