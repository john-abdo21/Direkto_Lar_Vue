<template>

    <button class="w-12 sm:w-14 h-full flex items-center justify-center bg-[#001E4A]" @click.stop="clickBell">
        <img src="../../assets/bell.svg" alt="" class="sm:hidden w-5"/>
        <span style="color: white" class="text-xs">{{messages.length}}</span>

        <img src="../../assets/phone-menu.svg" alt="" class="hidden sm:block"
        />
        <img src="../../assets/close.svg" alt="" class="hidden sm:block"
        />
    </button>

    <div
    v-if="isOpenBell"
    >

        <ul class="absolute mt-7
      list-none
      text-left
      rounded-lg
      bg-clip-padding
      left-auto
      right-1">
      <!-- <li class="bg-blue-100 w-72
      text-center shadow-md
      block py-2 w-full rounded-xl mt-2 "> as as a </li> -->
            <li v-for="(msg, index) in messages" :key="index" class="bg-blue-100 w-72
      text-center shadow-md
      block py-2 w-full rounded-xl mt-2
      " @click.stop="checkNotification(index)">
                {{msg.desDescripción}}
                <img src="../../assets/ic_arrow-down.svg" alt="" class="sm:hidden float-right mr-2" v-show="isChecked[index]"/>
            </li>
        </ul>
    </div>


</template>

<script>
    import {inject, onMounted, ref, watch} from 'vue';
    import store from '../../store';

    export default {

        name: "DropdownComponent",

        setup() {
            const emitter = inject('emitter');
            let messages = ref([{}]);
            let isChecked = ref([]);
            let isOpenBell = ref(false);

            emitter.on('notificationRegistered', (message) => {
                messages.value.push(message);
                // for(let i = 0; i < messages.value.length; i++) {
                //     isChecked.value[i] = true;
                // }
                isOpenBell.value = true;
            });

            onMounted(() => {

            });

            watch(() => messages.value, (current) => {
                if (current.length === 0) {
                    isOpenBell.value = false;
                }

            }, {deep: true});

            return {
                messages,
                isOpenBell,
                isChecked,
            }

        },

        data: function() {
            return {
            }
        },

        methods: {
            clickBell() {
                console.log(">>>>>> entramos aqui")
                this.isOpenBell = !this.isOpenBell;
            },
            checkNotification(index) {
                if (!this.isChecked[index]) {
                    let msg = this.messages[index];
                    console.log(msg);
                    store.dispatch('update_cod_notification', msg).then(res => {
                        console.log(res);
                        //this.messages.splice(this.messages.findIndex(m => m.codNotificacionUsuario === res), 1);
                    });
                    this.isChecked[index] = true;
                }
            },
        }
        ,
        mounted() {
            this.messages = this.$store.state.notifications;
            this.isOpenBell = true;

            for(let index = 0; index < this.messages.length; index++) {
                this.isChecked[index] = false;
            }
        }

    }
</script>

<style scoped>

</style>
