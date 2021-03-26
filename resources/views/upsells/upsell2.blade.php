<h1>upsell 2 Pain Patch</h1>
<div id="vueContainer">
<form id="upsell">
<button>paypal</button>

<input type="hidden" name="productId" value="244" />
<input type="hidden" name="orderId" value="{{request()->query('orderId')}}" />
<input type="hidden" name="shippingId" value="25"/>
</form>
</div>

<script src="https://unpkg.com/vue@next"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
const checkoutForm = {
    data(){
        return {
            message: 'hello',
            paypalResponse: '',
        }
    },
    methods: {
        paypalUpsell(){
            try{
                var form = document.getElementById('upsell');
                var formData = new FormData(form);

                axios.post('{{route("processUpsell")}}', formData)
                .then(function (response){
                    console.log(response.data.message);
                    this.paypalResponse = response.data.message;

                    console.log(this.paypalResponse);
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