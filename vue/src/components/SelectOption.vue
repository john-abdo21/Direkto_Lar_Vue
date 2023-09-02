<template v-if="selType !== 'tree'">
  <div v-if="selType !== 'tree'" class="flex shadow-tooltip rounded bg-white z-10 absolute w-full">
    <ul class="w-full">
      <li
        v-for="(option, index) in options"
        :key="index"
        :value="option.value"
        class="mb-1 cursor-pointer h-10 p-2 font-normal text-[0.7rem] relative"
        @click="$emit('selected', { value: option.value, name: option.name, name_variable:name, carea:option.carea })"
      >
        {{ option.name }}
        <img
          src="../assets/ic_arrow-down.svg"
          class="absolute top-1/2 -translate-y-1/2 right-4"
          alt=""
          v-if="selType === 'tree'"
        />
      </li>
    </ul>
  </div>

  <div v-if="selType === 'tree'" class="flex shadow-tooltip rounded bg-white z-10 absolute w-full">
            <ul class="w-full">
                <li
                        v-for="(option, index) in optionsF"
                        :key="index"
                        :value="option.value"
                        class="mb-2 cursor-pointer h-10 p-2 font-normal text-[0.7rem] relative"
                        @click="onListClick(option)"
                >
                    {{option.name}}
                    <img
                            src="../assets/ic_arrow-down.svg"
                            class="absolute top-1/2 -translate-y-1/2 right-4"
                            alt=""
                            :class="{
            'rotate-180': treeOpen && optionsF[index].value === curOptionValue,
            'rotate-0': !treeOpen && optionsF[index].value !== curOptionValue,
          }"

                    />
                </li>

                <div v-if="treeOpen" class="bg-gray-100">
                    <li
                            v-for="(option, index) in optionsM"
                            :key="index"
                            :value="option.value"
                            class="mb-2 cursor-pointer h-10 p-2 font-normal text-[0.7rem] relative"
                            @click="onSubListClick(option)"
                    >{{option.name}}

                    </li>
                </div>
                <li
                        v-for="(option, index) in optionsE"
                        :key="index"
                        :value="option.value"
                        class="mb-2 cursor-pointer h-10 p-2 font-normal text-[0.7rem] relative"
                        @click="onListClick(option)"
                >
                    {{option.name}}
                    <img
                            src="../assets/ic_arrow-down.svg"
                            class="absolute top-1/2 -translate-y-1/2 right-4"
                            alt=""
                            :class="{
            'rotate-180': treeOpen && optionsE[index].value === curOptionValue,
            'rotate-0': !treeOpen && optionsE[index].value !== curOptionValue,
          }"

                    />
                </li>
                <li
                class="mb-2 cursor-pointer h-10 p-2 font-normal text-[0.7rem] relative"
                @click="onListClickClean"
                >
                  Limpiar Filtro
                  <img
                            src="../assets/cleaning.svg"
                            class="absolute top-1/2 -translate-y-1/2 right-4"
                            alt=""
                  />

                </li>

            </ul>
        </div>
</template>
<script>
export default {
  name: "select-component",
  props: {
            options: Array,
            selType: String,
            name: String,
            treeOptions: Array,
            treeIndex: Number,
        },
  data: function () {
      return {
          selIndex: 0,
          treeOpen: false,
          optionsF: [],
          optionsE: this.options,
      };
  },
  computed: {
            optionsM: function() {
                return this.treeOptions;
            },
            curOptionValue: function() {
                return this.options[this.selIndex].value;
            }
  },
  methods: {
            onSubListClick(option) {
                this.$emit('treeSelected', {
                    selType: this.selType,
                    value: option.value,
                    name: option.name,
                    treeIndex: this.treeIndex
                });
                this.treeOpen = !this.treeOpen;
            },
            onListClick(option) {
                this.selIndex = this.options.findIndex(o => o.value === option.value);
                this.optionsF = this.options.slice(0, this.selIndex + 1);
                this.optionsE = this.options.slice(this.selIndex + 1, this.options.length);
                this.treeOpen = !this.treeOpen;
                this.$emit('selected', {
                    selType: this.selType,
                    value: option.value,
                    name: option.name,
                    name_variable: name,
                    carea: option.carea,
                    index: this.selIndex,
                })
            },
            onListClickClean(){

              this.$emit('selected', {
                    selType: 'clean',
                    value: -99,
                    name: '',
                    name_variable: '',
                    carea: 0,
                    index: 0,
                })


            }



        },

};
</script>
