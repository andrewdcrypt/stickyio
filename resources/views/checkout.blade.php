<h1>Checkout Page</h1>



<div id="vueContainer">
<form id="checkout" @submit.prevent="paypalCheckout" >
<input type="text" name="firstName" placeholder="firstName"><br>
<input type="text" name="lastName" placeholder="lastName"><br>
<input type="text" name="address" placeholder="address"><br>
<input type="text" name="address2" placeholder="address2"><br>
<input type="text" name="city" placeholder="city"><br>
<input type="text" name="state" placeholder="state"><br>
<input type="text" name="zip" placeholder="zip"><br>
<input type="text" name="phone" placeholder="phone"><br>
<input type="text" name="email" placeholder="email"><br>
<button>Paypal</button>
</form>
<div v-html="paypalResponse">
</div>
</div>


<script src="https://unpkg.com/vue@next"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
const checkoutForm = {
    data(){
        return {
            message: '',
            paypalResponse: "",
        }
    },
    methods: {
        paypalCheckout(){
            try{
                var form = document.getElementById('checkout');
                var formData = new FormData(form);

                axios.post('{{route("processCheckout")}}', formData)
                .then(function (response){
                    console.log(response.data.message);
                    this.paypalResponse = response.data.message;
                    console.log(this.paypalResponse);
                    if (response.data.response === 'pass'){
                        window.location.href=this.paypalResponse;
                    }else{
                        alert($response.data.message);
                    }
                }.bind(this))
                .then(function (error){
                    console.log(error);
                });
            }catch(err){
                console.log(err.message);
            }
            
        }
    }
}
Vue.createApp(checkoutForm).mount('#vueContainer');
</script>