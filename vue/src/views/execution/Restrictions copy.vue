<template>
  <!-- <div
    v-if="isLoading == false"
    class="h-full flex justify-center sm:items-start"

  >
  <loading

        v-model:active=isLoadingTrue
        :can-cancel="false"
        :is-full-page=true
        loader="dots"
    />


  </div> -->
  <div >
    <Breadcrumb :paths="['Inicio', 'Análisis de restricciones']" :settingFlag="true" />
    <Indicator
      :header="'Análisis de restricciones'"
      :paragraph="'Acá podrás visualizar las restricciones de tus proyectos y entrar al detalle de cada uno'"
      :buttonText="'Ver indicadores'"
    />
    <div class="mb-8 border border-[#D0D9F1] rounded-lg">
      <DataTable
        :tableType="'common'"
        :cols="headerCols"
        :rows="rows"
        class="sm:hidden text-[0.6rem]"
      >
        <template #default="{ row, index }">
          <RestrictionTableRow
            :row="row"
            :index="index"
            :users="users"
            @openModal="openModal"
            @selectUserFunc="selectUserFunc"
            @openConstraintPage="openConstraintPage"
          />
        </template>
      </DataTable>

      <div class="sm:flex flex-col justify-center md:hidden">
        <SquareBox v-for="(row, index) in rows" :key="index">
          <div class="flex justify-between items-center mb-4 mt-2">
            <span class="text-base">{{ row.desnombreproyecto }}</span>
            <button
              class="h-8 border-2 border-[#EB5D00] rounded px-4 text-xs text-orange"
              @click="openConstraintPage({id: row.codProyecto, nameProy: row.desnombreproyecto })"
            >
              Ingresar
            </button>
          </div>
          <div class="flex mb-2">
            <span class="text-xs leading-5 mr-1"
              >Estado: </span
            >
            <img
              src="../../assets/edit.svg"
              @click="openModal({ param: 'editStatus', id: row.codProyecto })"
              alt=""
            />
          </div>
          <div class="flex mb-2">
            <span class="text-xs leading-5 mr-1"
              >No retrasadas: {{ row.indNoRetrasados }}</span
            >
          </div>
          <div class="flex mb-2">
            <span class="text-xs leading-5 mr-1"
              >Retrasadas: {{ row.indRetrasados }}</span
            >
          </div>
          <div class="flex mb-2">
            <span class="flex flex-col text-[0.6rem]">
              <span v-for="(equipment, index) in row.integrantes" :key="index">{{ equipment }}</span>
            </span>
          </div>
          <div class="flex mb-4">
            <span class="text-xs leading-5 mr-1">Acciones usuario:</span>
          </div>
          <div class="flex mb-2 justify-between">
            <div class="flex" @click="selectUserFunc(row.id); openModal({ param: 'selectusers', id: row.desProyIntegrante, index: index })">
              <img
                src="../../assets/tooltip-person-add-active.svg"
                class="mr-1"
                alt=""
              />
              <span class="text-xs text-orange">Usuarios</span>
            </div>
            <!-- <div class="flex" @click="openModal({ param: 'edit', id: row.id })">
              <img
                src="../../assets/tooltip-edit-active.svg"
                class="mr-1"
                alt=""
              />
              <span class="text-xs text-orange">Editar</span>
            </div> -->
            <!-- <div
              class="flex"
              @click="openModal({ param: 'delete', id: row.id })"
            >
              <img
                src="../../assets/tooltip-delete-active.svg"
                class="mr-1"
                alt=""
              />
              <span class="text-xs text-orange">Eliminar</span>
            </div> -->
          </div>
        </SquareBox>
      </div>

    </div>
    <AdvertisingBig :width="928" :height="100" />
    <Advertising />
    <AddPerson
      v-if="modalName === 'add'"
      @closeModal="closeModal"
      @addUser="addUser"
    />
    <EditPerson
      :users="users"
      v-if="modalName === 'edit'"
      @closeModal="closeModal"
      @editUser="editUser"
    />
    <DeletePerson
      :users="users"
      v-if="modalName === 'delete'"
      @closeModal="closeModal"
      @deleteUser="deleteUser"
    />
    <Success
      :header="successHeader"
      :buttonStr="'Entendido'"
      v-if="modalName === 'success'"
      @closeModal="closeModal"
    />
    <Confirm
      :confirmHeader="'Eliminar usuario'"
      :header="confirmAskingHeader"
      :paragraphs="confirmPagraphs"
      :buttons="confirmButtons"
      :val="confirmVal"
      v-if="modalName === 'confirm'"
      @closeModal="closeModal"
      @confirmStatus="confirmStatus"
    />
    <EditStatus
      v-if="modalName === 'editStatus'"
      :data="data"
      @closeModal="closeModal"
      @editStatusSave="editStatusSave"
    />
    <SelectUsers
      v-if="modalName === 'selectusers'"
      @closeModal="updateMember"
      v-model="selUsers"
      :rowId="seluserId"
      :rowIndex = "seluserIndex"
    />
  </div>
</template>

<script>
import Breadcrumb from "../../components/Layout/Breadcrumb.vue";
import Indicator from "../../components/Layout/Indicator.vue";
import Advertising from "../../components/Layout/Advertising.vue";
import DataTable from "../../components/DataTable.vue";
import AdvertisingBig from "../../components/Layout/AdvertisingBig.vue";
import RestrictionTableRow from "../../components/RestrictionTableRow.vue";
import AddPerson from "../../components/AddPerson.vue";
import EditPerson from "../../components/EditPerson.vue";
import DeletePerson from "../../components/DeletePerson.vue";
import Success from "../../components/Success.vue";
import Confirm from "../../components/Confirm.vue";
import EditStatus from "../../components/EditStatus.vue";
import SelectUsers from "../../components/SelectUsers.vue";
import SquareBox from "../../components/SquareBox.vue";
import store from "../../store";
import Loading from 'vue-loading-overlay';

export default {
  name: "restrictions-view",
  components: {
    Loading,
    SquareBox,
    Breadcrumb,
    Indicator,
    Advertising,
    DataTable,
    AdvertisingBig,
    RestrictionTableRow,
    AddPerson,
    EditPerson,
    DeletePerson,
    Success,
    Confirm,
    EditStatus,
    SelectUsers,
  },
  data: function () {
    return {
      pageloadflag:false,
      rows:[],
      rowId: '',
      rowIndex:'',
      successHeader: "",
      modalName: "",
      confirmVal: "",
      confirmType: '',
      confirmHeader: "",
      confirmAskingHeader: "",
      confirmPagraphs: [],
      confirmButtons: [],
      headerCols: {
        analysis: "Análisis de restricciones",
        data: "Datos",
        project_name: "Nombre del proyecto",
        restriction: "Restricciones",
        equipment: "Equipo",
        action: "Acciones",
      },
      selUsers: [],
      seluserId: null,
      seluserIndex: 0 ,
      currentRes: {}
    };
  },
  methods: {
    openModal: function (param) {
      if (typeof param !== "string") {
        this.rowId    = param.id;
        this.rowIndex = param.index;
        param = param.param;
      }
      this.modalName = param;
    },
    closeModal: function () {
      this.modalName = "";
    },
    updateMember: function() {
      store.dispatch('update_member', this.currentRes);
      //console.log(this.currentRes)
      this.modalName = ""
    },
    addUser: function(payload) {
      this.$store.commit({
        type: 'addUser',
        id: this.rowId,
        email: payload.email
      });
      this.closeModal();
    },
    editUser: function (payload) {
      this.$store.commit({
        type: 'editUser',
        id: this.rowId,
        users: payload.users
      });
      this.successHeader = "¡Cambios guardados con éxito!";
      this.openModal("success");
    },
    deleteUser: function(payload) {
      this.confirmHeader = "Eliminar usuario";
      this.confirmVal = payload.param;
      this.confirmAskingHeader = "¿Seguro que deseas eliminar al usuario "+ this.confirmVal +"?";
      this.confirmPagraphs = ['Si lo eliminas y luego lo necesitas tendrás que volver a agregarlo,'];
      this.confirmButtons = ['Sí, eliminar', 'No, mantener'];
      this.confirmType = 'deleteUser';
      this.openModal("confirm");
    },
    confirmStatus: function(payload) {
      if (!payload.param) {
        this.$store.commit({
          type: this.confirmType,
          id: this.rowId,
          email: this.confirmVal
        });
        this.successHeader = "¡Usuario eliminado con éxito!";
        this.openModal('success');
      } else {
        this.closeModal();
      }
    },
    editStatusSave: function() {
      this.$store.commit({
        type: 'toggleData',
        id: this.rowId
      });
      this.successHeader = "¡Estado cambiado con éxito!";
      this.openModal('success');
    },
    selectUserFunc: function(payload) {
      // this.selUsers = this.$store.state.restriction_rows[payload-1].users;
      // this.currentRes = this.$store.state.restriction_rows[payload-1]
      let buscar         =   [payload.codProyecto]
      let datos          = buscar.map((id) => (this.$store.state.restriction_rows_real.find(x => x.codProyecto == id)));
      this.selUsers      = datos[0].integrantesProy;
      this.seluserId     = payload.codProyecto
      this.seluserIndex  = payload.index
      console.log(" Impresion de lo que llenamos")
      console.log(datos)
      console.log(this.selUsers  )

    },
    openConstraintPage: function(payload) {
      store.dispatch('cleanListRestrictions');
      sessionStorage.setItem('constraintid', payload.id)
      sessionStorage.setItem('constraintNameProy', payload.nameProy)
      console.log(payload)
      this.$router.push({
        name: 'add_restrictions'
      })
    }
  },
  computed: {
    rows: function() {

     //store.dispatch('get_restrictions')
      return this.$store.state.restriction_rows_real;
      // this.rows     = this.$store.state.restriction_rows_real;
    },
    users: function() {
      // buscar        = [this.seluserId]
      // datos         = buscar.map((id) => (this.$store.state.restriction_rows_real.find(x => x.codProyecto == id)));
      //let datos = this.$store.state.restriction_rows_real[this.seluserIndex].integrantesProy

      //  try {

      //  } catch (error) {

      //  }
      return this.$store.getters.usersRestrictions(this.rowId);

      // return  this.$store.state.restriction_rows_real[this.seluserIndex].integrantesProy //this.$store.getters.users(this.rowId);
    },
    // data: function() {
    //   return this.$store.getters.data(this.rowId);
    // },
    isLoadingTrue(){
       return true
    },
    isLoading: function(){
      return this.pageloadflag
    }
  },
  mounted: function(){

     store.dispatch('get_restrictions');
     store.dispatch('get_infoPerson');
    // await store.dispatch('get_project').then((response) => {
    // //  this.rows     = this.$store.state.restriction_rows_real;

    // });

    this.pageloadflag = true

  }
};
</script>
