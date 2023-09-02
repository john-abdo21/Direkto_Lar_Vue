
<script>
export default {
  name: "ResizableMinHeigth",
  methods: {
    resizeDiv(event) {
      event.target.style.height = "auto";
      event.target.style.height = (event.target.scrollHeight+50) + "px";
    }
  },
  mounted() {
    this.$nextTick(() => {
      // DOM updated
      this.$el.setAttribute(
        "style",
        "height:" + (this.$el.scrollHeight+50) + "px;overflow-y:hidden;"
      );
      this.$el.addEventListener("input", this.resizeDiv);
    });
  },
  beforeDestroy() {
    this.$el.removeEventListener("input", this.resizeDiv);
  },
  render() {
    return this.$slots.default[0];
  }
};
</script>
