<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<div id="app" v-bind:title="title" v-bind:attrib="attrib">
  {{ message }}
  <small>{{ sub }}</small>

  <ul>
    <li v-for="todo in todos">
      {{ todo }}
    </li>
  </ul>
  <br>
  <p v-on:click="onchange">{{ change }}</p>

</div>




<script type="text/javascript">
var app = new Vue({
el: '#app',
data: {
  message: 'Hello Vue!',
  sub: 'sub',
  title: 'some title',
  attrib: 'some attrib value',
  todos: ['learn', 'vue'],
  change: "Click to change me",
  swap: "Click to change me back"
},
methods: {
  onchange: function (){
    var temp = this.change;
    this.change = this.swap;
    this.swap = temp;
  }
}
})
</script>
