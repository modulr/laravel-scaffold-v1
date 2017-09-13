<template>
  <div class="quotes">
    <div class="modal fade" id="modalAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Quote</h4>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" action="index.html" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-lg" placeholder="Name" required v-model="quote.name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Designer *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Designer" required required v-model="quote.designer">
                              <option value="1">Dummie Option</option>
                              <option value="2">Dummie Option</option>
                              <option value="3">Dummie Option</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Seller *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Seller" required v-model="quote.seller">
                              <option value="1">Dummie Option</option>
                              <option value="2">Dummie Option</option>
                              <option value="3">Dummie Option</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Customer *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Customer" required v-model="quote.customer">
                              <option value="1">Dummie Option</option>
                              <option value="2">Dummie Option</option>
                              <option value="3">Dummie Option</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ammout *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-lg" placeholder="Ammout" required v-model="quote.ammount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Request Date *</label>
                        <div class='input-group date'>
                            <input type='date' class="form-control" v-model="quote.request_date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Delivery Date *</label>
                        <div class='input-group date'>
                            <input type='date' class="form-control" v-model="quote.delivery_date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" @click="store">Add</button>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      quote: {
        project: '1',
        close_date: "2017-08-05"
      }
    }
  },
  methods: {
    add () {
      $('#modalAdd').modal('show');
    },
    store: function (e) {        
        axios.post('/quote/store', this.quote)
        .then(response => {
            console.log(response)
            var btn = $(e.target).button('reset')
            $('#modalAdd').modal('hide');
        })
        .catch(error => {
            this.error = error.response.data;
            var btn = $(e.target).button('reset')
        });
    }
  }
}
</script>
