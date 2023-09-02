<template>
<div>
<Breadcrumb
      :paths="['Inicio', 'Información personal']"

    />
<div class="justify-between ">

  <div class=" justify-between sm:flex-col px-40">
    <span class="text-2xl mb-2">Hola , {{userData.name}}</span>
  </div>

  <div class=" justify-between sm:flex-col px-40">
    <img
        :src="userData.img_source"
        :class="{
          'img-size': true,
          'img-square': true,
          'img-circular': true,
        }"

      />
  </div>

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
        <span class="text-1xl mb-2 ">Nombre</span>
        <span class="mb-8 text-xs">{{userData.name}}</span>

        <span class="text-1xl mb-2">Celular</span>
        <span class="mb-8 text-xs">{{userData.celular}}</span>

        <span class="text-1xl mb-2">Cargo</span>
        <span class="mb-8 text-xs">Arquitecto</span>

      </div>
      <div class="flex flex-col w-[48%] sm:w-full">
        <span class="text-1xl mb-2">Apellido</span>
        <span class="mb-8 text-xs">{{userData.lastname}}</span>

        <span class="text-1xl mb-2">Empresa</span>
        <span class="mb-8 text-xs">{{userData.nombreempresa}}</span>

        <span class="text-1xl mb-2">Correo Empresarial</span>
        <span class="mb-8 text-xs">{{userData.email}}</span>


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
<div class="flex flex-col w-[38%] sm:w-full">


    <button class=" h-14 sm:w-full bg-orange text-white text-base pt-4 pb-4 rounded"
    @click="editPerson">Editar Informacion</button>




</div>

</div>


</div>
</div>

</template>

<script>
import store from "../../store";
import Breadcrumb from "../../components/Layout/Breadcrumb.vue";
// import { useRouter } from "vue-router";
// import { computed, ref } from "vue";


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

    editPerson: function (index) {

    this.$router.push({
      name: 'person_edit'
    })

    },

    load_data_person() {

    let data_person = this.$store.state.user.data;
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

  },
  mounted: function() {

      console.log(">>>>> verificamos viene de user.data")
      console.log(this.$store.state.user.data)
      store.dispatch("get_infoPerson").then((response) => {


        this.load_data_person()
        this.disabled = false

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
