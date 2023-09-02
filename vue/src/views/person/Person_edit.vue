<template>

<div>
<Breadcrumb
      :paths="['Inicio', 'Editar Información personal']"

    />
<div class="justify-between ">
  <div class="flex justify-between sm:flex-col px-40">
    <span class="text-2xl mb-2">Hola , {{userData.name}}</span>
  </div>

  <div class="flex justify-between sm:flex-col px-40 ">
    <img
        :src="userData.img_source"
        :class="{
          'img-size': true,
          'img-square': true,
          'img-circular': true,
        }"
      />


  </div>
  <nav><br></nav>
  <div class="flex justify-between sm:flex-col px-40">
    <div
      class="flex flex-col h-10 text-[#616E8E] group cursor-pointer px-5"
      @click="handleClick('edit')"
    >

    <input
                                        hidden
                                        type="file"
                                        ref="photoFile"
                                        accept=".jpg, .jpeg, .png"
                                        @change="addPerfilPhoto"
                                    />
      <img
        src="../../assets/tooltip-edit.svg"
        alt=""
        class="ml-6 mr-3 group-hover:content-editActive"
      />
      <w-btn class="text-1xl group-hover:text-orange" @click="$refs.photoFile.click()"
        >
        Editar
      </w-btn>
    </div>

  </div>
  <div class="flex justify-between sm:flex-col px-40">
  <div class="flex flex-col w-[48%] sm:w-full">
    <!-- <span class="text-2xl mb-2">Nombre</span>
    <span class="mb-8">{{info_person[0].name}}</span> -->
    <input
    :disabled="disabled"
    type="text"
    name=" nombre"
    v-model="userData.name"
    :errors="errors"
    placeholder="Ingresar Nombre"
    class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-4 sm:mb-8">


    <input
    :disabled="disabled"
    type="text"
    name="celular"
    v-model="userData.celular"
    :errors="errors"
    placeholder="Ingresar Celular"
    class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-4 sm:mb-8">

    <select
          name="codcargo"
          v-model="userData.codCargo"
          :errors="errors"
          class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-4 sm:mb-8"

          >
          <option disabled value="">Seleccionar un cargo</option>
          <option
          v-for="item in cargos" v-bind:key="item.codCargo" v-bind:value = "item.codCargo">
            {{ item.nameCargo }}
          </option>

  </select>

  </div>
  <div class="flex flex-col w-[48%] sm:w-full">
    <input
    :disabled="disabled"
    type="text"
    name="lastname"
    v-model="userData.lastname"
    :errors="errors"
    placeholder="Ingresar Nombre"
    class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-4 sm:mb-8">

    <input
    :disabled="disabled"
    type="text"
    name="nombreempresa"
    v-model="userData.nombreempresa"
    :errors="errors"
    placeholder="Ingresar Nombre"
    class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-4 sm:mb-8">

    <input
    :disabled="disabled"
    type="text"
    name="email"
    v-model="userData.email"
    :errors="errors"
    placeholder="Ingresar Nombre"
    class="h-[52px] border border-[#8A9CC9] rounded px-4 mb-10 sm:mb-8">


  </div>

</div>

<div class="flex flex-col w-[100%] sm:w-full">

<div class="seperator w-300">
  <h5></h5>
</div>

</div>
<br>
<div class="flex justify-between sm:flex-col px-40">

  <div class="flex flex-col w-[48%] sm:w-full">

</div>
<div class="flex flex-col w-[38%] sm:w-full ">
    <button class="  bg-orange text-white rounded justify-between pt-4 pb-4 "
    @click="personEditar">Guardar Cambios</button>

</div>

</div>


</div>
</div>

</template>

<script>

import Breadcrumb from "../../components/Layout/Breadcrumb.vue";
import store from "../../store";
export default {
  name: "white-project-component",
  components: {
    Breadcrumb
  },
  data: function () {
    return {
      nameProyecto:"",
      userData:[],
      cargos: [],
      disabled:true,
      // info_person:[],
      // img_source:"",
      disabledItems:true,
    }
  },
  methods: {

    load_data_person() {

      let data_person = this.$store.state.infoPerson.data_save;
      let data_img    = this.$store.state.infoPerson.img;
      this.userData['id']             = data_person.id
      this.userData['name']           = data_person.name
      this.userData['celular']        = data_person.celular
      this.userData['codCargo']       = data_person.codCargo
      this.userData['lastname']       = data_person.lastname
      this.userData['nombreempresa']  = data_person.nombreempresa
      this.userData['email']          = data_person.email
      this.userData['img_source']     = data_img
      console.log(data_person)
      console.log(">>>> aqui vemos lo que se cargo")
      console.log(this.userData)


    },

    personEditar(){
      let point = this
      point.disabled = true
      let payload    = point.userData
      console.log(payload)
      store.dispatch('upd_infoPerson', payload).then((response) => {

        point.disabled = false
        this.$router.push({
            name: 'person'
        });


      });


    }

  },
  computed:{
    // info_person: function() {
    //   return this.$store.state.infoPerson.data;
    // },
    // img_source:function(){
    //   return this.$store.state.infoPerson.img;
    // },




  },
  mounted: function() {




    store.dispatch("get_infoPerson").then((response) => {

      this.load_data_person()
      this.disabled = false

      store.dispatch("get_cargos").then((response) => {

      this.cargos = this.$store.state.cargos;


      });

    });


  }
}
</script>

<style>
.img-size {
  height: 100px;
}

.img-square {
  border-radius: 0;
  transition: all 0.2s;
}

.img-circular {
  border-radius: 50%;
  transition: all 0.2s;
}

.seperator h5 {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0 1em;
}

.seperator h5::before,
.seperator h5::after {
  content: "";
  display: block;
  flex-grow: 1;
  height: 1px;
  background: #ccc;
}

.seperator h5 span {
  padding: 0 2em;
}
</style>
