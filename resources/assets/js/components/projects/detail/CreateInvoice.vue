<template>
  <div class="modal right fade" id="modalAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Invoice</h4>
                </div>
                <div class="modal-body">                    
                    <form class="form-horizontal" action="index.html" method="post">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Project</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute">{{project.name}}</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Customer</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute" v-if="project.client">{{project.client.customer.name}}</small>
                                    <small class="text-mute" v-else>Data not available.</small>
                                </p>
                            </div>
                        </div>                        
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label class="col-sm-3 control-label required">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" class="form-control input-lg" placeholder="Description" required v-model="invoice.description">
                                </textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Amount </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-lg" placeholder="Amount" required v-model="invoice.amount">
                            </div>                            
                        </div>
                        <div class="form-group" :class="{'has-error': error.service}">
                            <label class="col-sm-3 control-label required">Quotes</label>
                            <div class="col-sm-9">
                                <multiselect v-model="invoice.quotes" track-by="name" label="name" :options="quotes" placeholder="Select a Quote" :multiple="true"></multiselect>
                                <span class="help-block" v-if="error.service">{{error.service[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" v-for="(item,index) in invoice.quotes">
                            <label class="col-sm-3 control-label required">Amount for {{item.name}} </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-lg" placeholder="Amount" required v-model="invoice.quotes[index].invoice_amount">
                                <span class="help-block" v-if="totalQuotes">El total de los importes debe coincider con el total de la factura</span>                                
                            </div>                            
                        </div>
                        <!-- <div class="form-group">
                            <div class="col-md-12">
                                <dropzone id="myVueDropzoneInvoice" ref="myVueDropzoneInvoice"
                                    url="/invoices/file" :use-font-awesome=true
                                    :use-custom-dropzone-options=true :dropzoneOptions="dzOptions"
                                    v-on:vdropzone-sending="addingParams"
                                    v-on:vdropzone-success="uploadSuccess">               
                                </dropzone>
                            </div>
                        </div>                         -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="store">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import Multiselect from 'vue-multiselect';
import Dropzone from 'vue2-dropzone';
export default {
    props: ['list', 'invoice', 'project', 'quotes'],
    components: {
        Multiselect,
        Dropzone
    },
    data() {
        return {
            error: {},
            dzOptions: {
                acceptedFileTypes: '.pdf',
                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                autoProcessQueue: false
            },       
            isValid: false  
        }
    },
    computed: {
        totalQuotes () {
            let total = 0.00
            this.invoice.quotes.forEach(item => {
                total += item.invoice_amount ? parseFloat(item.invoice_amount) : 0.00
            })            
            if (this.invoice.amount > 0 && (parseFloat(this.invoice.amount) !== parseFloat(total))) {
                this.isValid = true
                return true
            } else {
                this.isValid = false
                return false
            }
        }
    },
    methods : {        
        store (e) {                        
            var btn = $(e.target).button('loading')
            axios.post('/invoices/store', this.invoice)
                .then(response => {                    
                    // console.log(response)
                    // this.invoice.quotes.forEach(item => {
                    //     this.$parent.quotes.map(quote => {
                    //         if (quote.id === item.id) {
                    //             console.log("InvoiceQuote: " + item.id)
                    //             console.log("ParentQuote: " + quote.id)                                
                    //             quote.invoices.push(response.data)
                    //         }
                    //     })                
                    // })
                    // this.$parent.invoice = response.data
                    this.error = {};
                    axios.get(`/quote/all?project=${this.project.id}`)
                    .then(response => {
                        this.$parent.quotes = response.data.quotes.data
                        console.log(this.$parent.quotes)
                        // this.loadingQuotes = false
                    });
                    // this.$refs.myVueDropzoneInvoice.processQueue()
                    var btn = $(e.target).button('reset')
                    $('#modalAdd').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    console.log(error.response.data);
                    var btn = $(e.target).button('reset')
                });      
        },
        addingParams (file, xhr, formData) {
            formData.append('invoice_id', this.invoice.id);
            // formData.append('currency', this.invoice.currency);
            // formData.append('description', this.invoice.description);
            // let quotes = this.invoice.quotes.map(item => item.id)            
            // formData.append('quotes', quotes);
            // console.log('agregando...')
        },        
        uploadSuccess (file, response) {
            console.log(response)
            console.log("enviado")
            // this.invoice.quotes.forEach(item => {
            //     this.$parent.quotes.map(quote => {
            //         if (quote.id === item.id) {
            //             quote.invoices.push(response)
            //         }
            //     })                
            // })
            $('#modalAdd').modal('hide')
        },
    }
}
</script>