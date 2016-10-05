<div class="row godpad">
    <div class="col-md-6" style="border: 1px solid black">
      <legend>Godpad <?php echo $i; ?></legend>
      <form role="form" >
          <div class="form-group">
            <div>
              <button id="button1id" name="button1id" class="btn btn-success">New Customers</button>
              <button id="button2id" name="button2id" class="btn btn-danger">Existing Customers</button>
            </div>
          </div>
            
          <div class="form-group">
            <label for="customerID">Customer ID*</label>
            <input type="text" class="form-control" id="customerID" value="" />
            <label for="customerID">Order No.</label>
            <input type="text" class="form-control" id="customerID" value="" />
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" value="" />
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                 
                <label for="telephone1">
                  Telephone 1
                </label>
                <input type="text" class="form-control" id="telephone1" />
              </div>
              <div class="form-group">
                 
                <label for="telephone2">
                  Telephone 2
                </label>
                <input type="text" class="form-control" id="telephone2" />
              </div>
            </div>
              
            <div class="col-md-6">
              <div class="form-group">
                 
                <label for="handphone1">
                  Handphone 1
                </label>
                <input type="text" class="form-control" id="handphone1" />
              </div>
              <div class="form-group">
                 
                <label for="handphone2">
                  Handphone 2
                </label>
                <input type="text" class="form-control" id="handphone2" />
              </div>
            </div>
          </div>
          
          <div class="form-group">
                 
            <div class="form-group">
              <label for="descriptions">Descriptions</label>
              <textarea class="form-control" id="textarea" name="descriptions"></textarea>
            </div>
          </div>
          
          <button type="submit" class="btn btn-danger">
            Delete Pad
          </button>
          <button type="submit" class="btn btn-success pull-right">
            Submit
          </button>
          <div class="form-group">
          </div>
        </form>
    </div>
    
  </div>