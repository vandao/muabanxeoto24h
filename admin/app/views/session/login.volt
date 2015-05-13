

  {{ content() }}

  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        {{ form('class': 'form-horizontal well') }}
          <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
              <h2>{{ label.normal('Page-Login-Header', false) }}</h2>
            </div>
          </div>

          {{ form.messageHorizontal(feedback) }}

          {{ form.renderHorizontal('email') }}
          {{ form.renderHorizontal('password') }}
          
          {{ form.renderHorizontal('remember_me') }}

          {{ form.renderHorizontal('csrf', ['value': security.getToken()]) }}

          <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-sign-in"></i>
                Sign in
              </button>

              {{ link_to('staffs/forgot-password', '<i class="fa fa-refresh"></i> Forgot password', 'class': 'btn btn-default') }}
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
