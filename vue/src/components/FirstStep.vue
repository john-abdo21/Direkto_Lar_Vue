<template>

  <div class="flex flex-col" >
    <span class="w-[80%] m-auto text-base mb-4">Crea tu primer proyecto</span>
    <div class="flex w-[80%] m-auto justify-between sm:flex-col">
      <div class="flex flex-col w-[40%] sm:w-full">
      <div class="mb-4 ">
        <input
          type="text"
          placeholder="Nombre del proyecto*"
          v-model="projectName"
          :class="{ 'invalid-input': (errors.projectName != undefined)  }"
          class="h-[32px] text-[0.7rem] w-full border border-[#8A9CC9] rounded px-4 "
          @focus="limpiarErrores()"
        />

        <div class="text-[0.7em]">{{ errors.projectName }}</div>
      </div>

        <div
           class="autocompleteel h-[32px] w-full mb-4 border border-[#8A9CC9] rounded "
           :class="{ 'invalid-input': (errors.business != undefined)  }"
        >
	  		<div class="w-full"
        >
          <input type="hidden" v-model="business">
		  		<input type='text' @keyup='loadSuggestions' placeholder='Empresa'
            class="pl-4 foco_empresa text-[0.7rem] pt-1.5 pb-1.5"
		  			v-model='searchText'
            @focus="(this.focoEmpresa = true); limpiarErrores();"


      			>	<br>
		  		<div class="w-[100%]  rounded bg-white border border-gray-300 px-4 py-2 space-y-1 relative z-50 foco_empresa"
          v-if="suggestiondata.length"

          >
		  		<ul>
              <li
              class="px-1 pt-1 pb-2 font-bold border-b border-gray-200 foco_empresa"
              >
              <!-- Mostrando {{ suggestiondata.length }} resultados -->

              <div class="flex justify-between sm:flex-col foco_empresa">
              <div class="flex flex-col w-[80%] foco_empresa text-[0.7rem]">
                Mostrando {{ suggestiondata.length }} resultados.

              </div>
              <div class="flex flex-col w-[10%] foco_empresa">

                <img
                  src="../assets/close.svg"
                  alt=""
                  class="w-[50%] cursor-pointer foco_empresa"
                  @click='itemSelected(-100)'

              />

              </div>
              </div>
              </li>
              <div class="overflow-x-auto h-40 foco_empresa">
                  <li
                  v-for= 'item in suggestiondata'
                  v-bind:key="item.cod_Empresa"
                  v-bind:value = "item.cod_Empresa"
                  @click='itemSelected(item.cod_Empresa);'
                  class="cursor-pointer hover:bg-gray-100 p-1 foco_empresa text-[0.7rem]"
                  >
                    {{ item.des_Empresa }}
                  </li>
              </div>
		  		</ul>
		  		</div>
	  		</div>
        <div class="text-[0.7em]">{{ errors.business }}</div>
	  	</div>


      <div class="mb-4 ">
        <input
          type="text"
          placeholder="Plazo"
          v-model="term"
          @keypress="onlyNumber"
          class="h-[32px] w-full border border-[#8A9CC9] rounded px-4 text-[0.7rem]"
          :class="{ 'invalid-input': (errors.term != undefined)  }"
          @focus="limpiarErrores()"
        />

        <div class="text-[0.7em]">{{ errors.coveredArea }}</div>


       </div>

        <div class=" mb-4">
        <div class="flex">
          <input
            type="text"
            placeholder="Área del Terreno"
            v-model="coveredArea"
            @keypress="onlyNumber"
            @focus="limpiarErrores()"
            class="h-[32px] w-[82%] border border-[#8A9CC9] rounded-l pl-4 pr-[116px] text-[0.7rem]"
            :class="{ 'invalid-input': (errors.coveredArea != undefined)  }"
          />

          <div class="w-[18%] border border-[#8A9CC9] flex items-center justify-center rounded-r px-4"
              :class="{ 'invalid-input': (errors.coveredArea != undefined)  }"
          >
            <p class="font-normal text-[0.7rem] ml-[10%]">m2</p>

          </div>
        </div>

        <div class="text-[0.7em]">{{ errors.coveredArea }}</div>

        </div>

        <div class=" mb-4 w-full">

          <select
          v-model="projectType"
          class="h-[32px] w-full border border-[#8A9CC9] rounded pl-4 selectPer2 text-[0.7rem]"
          :class="{ 'invalid-input': (errors.projectType != undefined)  }"
          @focus="limpiarErrores()"
          >
          <option disabled value="">Tipo de Proyecto</option>
          <option
          v-for="item in listaTiposproyectos" v-bind:key="item.codTipoProyecto" v-bind:value = "item.codTipoProyecto">
            {{ item.desTipoProyecto }}
          </option>

          </select>

          <div class="text-[0.7em]">{{ errors.projectType }}</div>

        </div>
      <div
          class="autocompleteel h-[32px] w-full mb-4 border border-[#8A9CC9] rounded "
          :class="{ 'invalid-input': (errors.ubigeo != undefined)  }"
      >
	  		<div >
          <input type="hidden" v-model="ubigeo">
		  		<input
                 type='text'
                 placeholder='Ubicación'
		  			     v-model='searchTextUbigeo'
                 @keypress=" searchTextUbigeoFlg = 0"
                 class="border border-[#8A9CC9] rounded px-4 foco_ubigeo text-[0.7rem] pt-1.5 pb-1.5"
                 @blur="focoUbigeo = true"
                 @focus="limpiarErrores()"

      			/>
          <br>
		  		<div

          v-if="loadSuggestionsUbigeo.length && searchTextUbigeoFlg == 0"
          class="w-[100%] rounded bg-white border border-gray-300 py-2 relative foco_ubigeo"
          >
		  		<ul class="z-2">
              <li
              class="px-1 pt-1 pb-2 font-bold border-b border-gray-200 foco_ubigeo"
              >
              <div class="flex justify-between sm:flex-col foco_ubigeo">
              <div class="flex flex-col w-[80%] foco_ubigeo text-[0.7rem]">
                Mostrando {{ loadSuggestionsUbigeo.length }} de {{ listaUbigeos.length }} resultados.

              </div>
              <div class="flex flex-col w-[5%] foco_ubigeo">

                <img
                  src="../assets/close.svg"
                  alt=""
                  class="w-[50%] cursor-pointer foco_ubigeo"
                  @click='itemSelectedUbigeo(-100)'

              />

              </div>
            </div>


              </li>
              <div class="overflow-x-auto h-40 foco_ubigeo">
                  <li
                  v-for= 'item in loadSuggestionsUbigeo'
                  v-bind:key="item.codUbigeo"
                  v-bind:value = "item.codUbigeo"
                  @click='itemSelectedUbigeo(item.codUbigeo)'
                  class="cursor-pointer hover:bg-gray-100 p-1 foco_ubigeo text-[0.7rem]"
                  >
                    {{ item.desUbigeo }}
                  </li>
              </div>
		  		</ul>
		  		</div>
	  		</div>
        <div class="text-[0.7em]">{{ errors.ubigeo }}</div>
	  	</div>

      </div>


      <div class="flex flex-col w-[40%] sm:w-full">
        <div class=" mb-4">
            <v-date-picker v-model="startDate" mode="date" class="rounded" >
              <template v-slot="{ inputValue, inputEvents }" >
                <input
                  class="h-[32px] w-full  border border-[#8A9CC9] rounded px-4 text-[0.7rem]"
                  :class="{ 'invalid-input': (errors.startDate != undefined)  }"
                  placeholder="Fecha de inicio*"
                  :value="inputValue"
                  v-on="inputEvents"
                  @focus="limpiarErrores()"
                />
              </template>
            </v-date-picker>
            <div class="text-[0.7em]">{{ errors.startDate }}</div>
        </div>

        <div class="mb-4 ">
          <div class="flex h-[32px] rounded ">
            <input
              type="text"
              placeholder="Monto referencial"
              v-model="referenceAmount"
              @keyup="formatNumber"
              @focus="limpiarErrores()"
              class="h-[32px] w-[82%] mb-4 border border-[#8A9CC9] rounded-l px-4 text-[0.7rem]"
              :class="{ 'invalid-input': (errors.referenceAmount != undefined)  }"
            />

            <div
              class="w-[18%] border border-[#8A9CC9] rounded-r "
              :class="{ 'invalid-input': (errors.codMoneda != undefined)  }"
            >
              <select
              v-model="codMoneda"
              class="flex  right-0 h-full justify-between border-[#8A9CC9] w-full selectPer1 text-[0.7rem] pl-3"
              @focus="limpiarErrores()"

              >
              <option
              v-for="item in listaMonedas" v-bind:key="item.codMoneda" v-bind:value = "item.codMoneda">
                {{ item.desSimbolo }}
              </option>
              </select>
            </div>
          </div>
          <div class="text-[0.7em]">{{ errors.referenceAmount }}{{ errors.codMoneda }}</div>
        </div>

        <div class="mb-4 ">
          <div class="flex h-[32px]">
              <input
                  type="text"
                  placeholder="Área techada"
                  v-model="builtArea"
                  @keypress="onlyNumber"
                  @focus="limpiarErrores()"
                  class="h-[32px] w-full mb-4 border border-[#8A9CC9] rounded-l px-4 w-[82%] text-[0.7rem]"
                  :class="{ 'invalid-input': (errors.builtArea != undefined)  }"
              />
              <div
                class="w-[18%] border border-[#8A9CC9] rounded-r px-4 flex justify-center items-center"
                :class="{ 'invalid-input': (errors.builtArea != undefined)  }"
              >
                  <p class="font-normal text-[#8A9CC9] ml-[10%] text-[0.7rem]">m2</p>
              </div>
          </div>
          <div class="text-[0.7em]">{{ errors.builtArea }}</div>
        </div>

        <input
          type="text"
          placeholder="País*"
          v-model="country"
          :errors="errors"
          readonly="readonly"

          class="h-[32px] w-full mb-4 border border-[#8A9CC9] rounded px-4 text-[0.7rem]"
        />
        <input
          type="text"
          placeholder="Dirección"
          v-model="address"
          class="h-[32px] w-full mb-4 border border-[#8A9CC9] rounded px-4 text-[0.7rem]"
        />



    </div>



    <!-- <span class="cursor-pointer" @click="openModal({param: 'newproject', id: 1})">Ver</span> -->


    <NewCompany
          v-if="modalName === 'newproject'"
          @closeModal   ="closeModal"
          @crearEmpresa = "datosProyecto"
          v-model="datapasar"

        />

    </div>
  </div>

</template>

<script>
// import autocomplete from 'vue-autocomplete-input-tag'
// import vClickOutside from 'v-click-outside'

import NewCompany from './NewCompany.vue'
import Loading from 'vue-loading-overlay';
export default {
  name: "first-step-component",
  components: {
    // autocomplete,
    Loading,
    NewCompany

  },
  // directives: {
  //     clickOutside: vClickOutside.directive
  //   },
  data: function () {
    return {
      searchTextUbigeo : "",
      searchTextUbigeoFlg:0,

      focoUbigeo:false,
      focoEmpresa:false,

      status_modal_np : false,
      rowId:0,
      modalName:"",
      datapasar: {},
      userid1: 0,
      searchText:'',
      searchSaved:'',
      suggestiondata:[],
      coveredAreaStatus: false,
      projectTypeStatus: false,
      districtStatus: false,
      projectName: "",
      // codEmpresa:"",
      business: "",
      term: "",
      coveredArea: "",
      projectType: "",
      ubigeo: "0",
      startDate: "",
      referenceAmount: "",
      area: "",
      builtArea: "",
      country: "Perú",
      address: "",
      codMoneda:"",
      placeholder:"",
      listaTiposproyectos: [],
      listaUbigeos:[],
      listaMonedas:[],
      attributes: [
        {
          key: 'today',
          // highlight: '##002B6B',
          highlight: true,
          dates: new Date(),
        },
      ],
      errors: {},
      test: {},
      items: [
      ],
    };
  },
  methods: {

    formatNumber() {
      // remueve los puntos que haya en el string monto
      let number = this.referenceAmount.replace(/\./g, '')

      // conserva solo los números en el string del monto
      number = number.replace(/[^0-9]/g, '')

      // da formato a los números
      let formattedNumber = new Intl.NumberFormat().format(number)

      // actualiza el valor de la variable "monto" con el nuevo valor
      this.referenceAmount = formattedNumber
    },

//     formatoMiles(valor) {
//       let num = parseFloat(valor.replace(/,/g, "")).toFixed(2);
//       let separadorDecimal = ".";
//       let separadorMiles = ",";
//       let signoMoneda = "";

//       // Separa los miles desde la posición decimal
//       let parteEntera = num.split(".")[0];
//       let parteDecimal = num.split(".")[1];

//       if (parteEntera.length > 3) {
//         // Agrega comas separadoras de miles y regresa el resultado
//         this.formatearNumerosComa(parteEntera, separadorMiles) +
//           separadorDecimal +
//           parteDecimal;
//       } else {
//         return valor;
//       }
//     },
//     formatearNumerosComa(numero, separadorMiles) {
//       let arrayNumero = numero.split("");
//       let contador = 0;

//       for (let i = arrayNumero.length - 1; i >= 0; i--) {
//         contador++;
//         if (contador % 3 === 0 && i !== 0) {
//           arrayNumero[i] = separadorMiles + arrayNumero[i];
//           contador = 0;
//         }
//       }

//       return arrayNumero.join("");
//     },

//     formatPrices(campo) {
//       this[campo] = parseFloat(this[campo])
//                         .toLocaleString('es-ES', {
//                            minimumFractionDigits: 2, maximumFractionDigits: 2
//                          })
//     },

//     formatPrices0(inputName) {
//       // aplicar formato al precio
//       this.referenceAmount = (this.referenceAmount).toString().replace(/\D/g, "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

//       // // aplicar formato al monto
//       // let monto = this.monto.replace(/[^\d]/g,'');
//       // this.monto = (monto/100).toLocaleString('es-MX');
//     },


//     formatNumber() {
//       // eliminar caracteres no numericos
//       let formatted = this.number.replace(/\D/g,'');
//       // insertar comas cada tres digitos ivertidos
//       formatted = formatted.split('').reverse().join('')
//           .replace(/(\d{3})/g, '$1,')
//           .split('').reverse().join('');
//       // asignar a v-model
//       this.number = formatted;
//     },

//     convertirdecimal: function (x) {
//   // Reemplaza todas las comas y cualquier carácter que no sea un dígito
//   x = x.toString().replace(/,/g, "").replace(/\D/g,"");

//   // Divide el número en miles, de tres en tres, y añade comas
//   var pattern = /(-?\d+)(\d{2})/;

//   while(pattern.test(x)) {
//     x = x.replace(pattern, "$1,$2");
//   }

//   return x;
// },

//   handleInput: function ($event) {
//     // Obtiene el valor ingresado por el usuario.
//     const value = $event.target.value;

//     // Modifica el valor ingresado para que tenga formato de miles.
//     const formattedValue = this.convertirdecimal(value);

//     // Establece el nuevo valor con formato de miles en el input.
//     $event.target.value = formattedValue;
//   },


    limpiarErrores: function (campo) {
      this.errors = [];
      // delete this.errors[campo];

    },

    dataVerificamos: function () {
      console.log(">>> entramos a  ver")
      console.log(this.term)

      let  cant_error  = 0
      if (!this.projectName.trim()) {
        this.errors.projectName = 'El campo Nombre es requerido';
        cant_error++;
      }

      if (this.business == '') {
        this.errors.business = 'El campo Empresa es requerido';
        cant_error++;
      }

      if (this.term.trim() == '') {
        this.errors.term = 'Ingresa valores correctos en el campo Plazo';
        cant_error++;
      }

      if (this.coveredArea.trim() == '') {
        this.errors.coveredArea = 'Ingresa valores correctos en el campo Area del Terreno';
        cant_error++;
      }

      if (this.projectType == '' ) {
        this.errors.projectType = 'Elige un tipo de proyecto.';
        cant_error++;
      }

      if (this.ubigeo == '0' || this.ubigeo == '') {
        this.errors.ubigeo = 'Elige una Ubicacion geografica de forma correcta.';
        cant_error++;
      }

      if (this.startDate == '' ) {
        this.errors.startDate = 'Elija la fecha requerida.';
        cant_error++;
      }

      if (this.referenceAmount == '') {
        this.errors.referenceAmount = 'Ingrese monto referencial.';
        cant_error++;
      }


      if (this.codMoneda == '') {
        this.errors.codMoneda = 'Elija el tipo de moneda del monto.';
        cant_error++;
      }


      if (this.builtArea == '') {
        this.errors.builtArea = 'Ingrese el area Techada';
        cant_error++;
      }

      console.log(cant_error+">>>>")

      return cant_error;
    },

    sinFocoEmpresa: function (param) {

            if (this.business == ''){

              this.searchText = ""
              this.suggestiondata = [];

            }else{
                  // let buscar = [this.business]
                  // let name   = buscar.map((id) => (this.suggestiondata.find(x => x.cod_Empresa == id).des_Empresa));

                  this.searchText     = this.searchSaved
                  this.suggestiondata = [];

            }
            this.focoEmpresa = false;

    },
    sinFocoUbicacion: function (param) {

      // if(this.focoUbigeo == true){

        // console.log(">>>>>> entramos por que cambio el blur del ubigeo")
        // console.log(this.ubigeo)

          if (this.ubigeo == '0' || this.ubigeo == '' ){

              this.searchTextUbigeo = ""
              this.suggestiondata   = [];

              }else{

              let buscar               = [this.ubigeo]
              this.searchTextUbigeoFlg = 1
              let name                 = buscar.map((id) => (this.listaUbigeos.find(x => x.codUbigeo == id).desUbigeo));
              this.searchTextUbigeo    = name[0]


          }

          this.focoUbigeo = false;

      // }

    },


    openModal: function (param) {
      if (typeof param !== "string") {
        this.rowId = param.id;
        param = param.param;
      }
      this.modalName = param;
    },
    closeModal: function () {
      this.searchText = ""
      this.business   = ""
      if (this.modalName === "") this.$store.commit("increaseHint");
      else this.modalName = "";
    },
    datosProyecto: function (datos) {

      this.$store.dispatch('save_newempresa', datos)
          .then((response) => {

            this.modalName      = "";
            this.suggestiondata = [];

            if (response["flag"] == 1){

              this.business   = response["registro"]["cod_Empresa"];
              this.searchText = response["registro"]["des_Empresa"];

            }

      });

    },

    loadSuggestions: function(e){
				var el = this;
				this.suggestiondata = [];

				if(this.searchText != ''){
          var enviamos = { buscar : this.searchText }
          this.$store.dispatch('get_buscar', enviamos)
          .then((response) => {
            let data = []
            //console.log(response)
            for (let index = 0; index < response.length; index++) {
              data.push({cod_Empresa:response[index]["cod_Empresa"], des_Empresa: response[index]["des_Empresa"]})
            }
            data.push({cod_Empresa:-999, des_Empresa: '+ Agregar Nueva Empresa'})
            el.suggestiondata  = data
          })

				}

			},

      itemSelectedUbigeo: function (cod){
      // console.log(cod)

        if (cod != -100){

          let buscar               = [cod]
          let name                 = buscar.map((id) => (this.listaUbigeos.find(x => x.codUbigeo == id).desUbigeo));
          // console.log(name[0])
          this.searchTextUbigeo    = name[0]
          this.ubigeo              = cod
          this.searchTextUbigeoFlg = 1

        }else{

          let buscar               = [this.ubigeo]
          this.searchTextUbigeoFlg = 1
          let name                 = buscar.map((id) => (this.listaUbigeos.find(x => x.codUbigeo == id).desUbigeo));
          this.searchTextUbigeo    = name[0]

        }

    },
    // itemSelectedUbigeo: function(id){
    //     let buscar            = [id]
    //     let name              = buscar.map((id) => (this.listaUbigeos.find(x => x.codUbigeo == id).desUbigeo));
    //     this.searchTextUbigeo = name
    //     this.ubigeo           = id
    // },
		itemSelected: function(id){

          if (id == -100){

            let buscar = [this.business]
            let name   = buscar.map((id) => (this.suggestiondata.find(x => x.cod_Empresa == id).des_Empresa));

            this.searchText     = name[0];
            this.suggestiondata = [];
            // this.business       = id



          }else if (id == -999){

            this.modalName      = 'newproject';

          }else{


            let buscar = [id]
            let name   = buscar.map((id) => (this.suggestiondata.find(x => x.cod_Empresa == id).des_Empresa));

            this.searchText     = name;
            this.searchSaved    = name;
            this.suggestiondata = [];
            this.business       = id


          }



		},
    handleClick: function(param) {
      if (param === 'coveredArea') this.coveredAreaStatus = !this.coveredAreaStatus;
      else if (param === 'projectType') this.projectTypeStatus = !this.projectTypeStatus;
      else if (param === 'district') this.districtStatus = !this.districtStatus;
    },
    onlyNumber: function ($event) {
      //console.log($event.keyCode); //keyCodes value
      let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
          $event.preventDefault();
      }
    },



  },
  computed: {


    loadSuggestionsUbigeo(e){
     let matches              = 0
    //  this.searchTextUbigeoFlg = 0
    //  console.log("entrando al final :"+this.searchTextUbigeoFlg.toString())

     if (this.searchTextUbigeo == '') {
             return []
     }
     return this.listaUbigeos.filter(ubigeo => {
      if (
             ubigeo.desUbigeo.toLowerCase().includes(this.searchTextUbigeo.toLowerCase())
             &&
             matches < 10
         ){
             matches++
             return ubigeo
          }

     });

    },

  },

  mounted() {

  this.$el.addEventListener('click',(e) =>
    {
       let elementClass = e.target.className;

        if (elementClass !== '') {

          if(this.focoEmpresa == true){

            if(elementClass.search('foco_empresa') < 0){
              this.sinFocoEmpresa()
            }

          }

          if(this.focoUbigeo == true){

            if(elementClass.search('foco_ubigeo') < 0){
              this.sinFocoUbicacion()
            }

          }


        }

    // // If element has no classes
    // else {
    //   console.log('An element without a class was clicked');
    // }
  });


  },



};

// document.addEventListener('click',(e) =>
//   {
//     // Get element class(es)
//     let elementClass = e.target.className;
//     // If element has class(es)
//     if (elementClass !== '') {
//       console.log(elementClass);
//     }
//     // If element has no classes
//     else {
//       console.log('An element without a class was clicked');
//     }
//   }
// );

</script>

<style>

.selectPer1 {
  text-indent: 1px;
  text-overflow: '';
  /* width: px; */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;

  background: transparent url("../assets/ic_arrow-down.svg") no-repeat 70%;


}
.selectPer2 {
  text-indent: 1px;
  text-overflow: '';
  /* width: px; */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;

  background: transparent url("../assets/ic_arrow-down.svg") no-repeat 92%;


}

 .ocultar{
      display: none;
 }

 .without_icon {

      background-image: none !important
 }

  .autocompleteel,.autocompleteel{
      width: 100%;


  }

  .autocompleteel input[type=text]{
      width: 100%;
      border: none;
}


  .invalid-input {
    border-color: red !important;
  }



</style>
