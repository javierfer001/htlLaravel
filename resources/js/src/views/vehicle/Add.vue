<template>
  <b-card-code
    title="Data Vehicle"
  >
    <validation-observer ref="simpleRules">
      <b-form>
        <b-row>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="year"
                rules="required"
              >
                <b-form-input
                  v-model="data.year"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Year"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="make"
                rules="required"
              >
                <b-form-input
                  v-model="data.make"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Make"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="model"
                rules="required"
              >
                <b-form-input
                  v-model="data.model"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Model"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="vin"
                rules="required|min:17"
              >
                <b-form-input
                  v-model="data.vin"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Vin"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-button
              variant="primary"
              type="submit"
              @click.prevent="validationForm"
            >
              Submit
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card-code>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  BFormInput, BFormGroup, BForm, BRow, BCol, BButton,
} from 'bootstrap-vue'
import { positive } from '@validations'
// eslint-disable-next-line import/extensions
import ToastificationContent from '@core/components/toastification/ToastificationContent'

export default {
  components: {
    BCardCode,
    ValidationProvider,
    ValidationObserver,
    BFormInput,
    BFormGroup,
    BForm,
    BRow,
    BCol,
    BButton,
  },
  data() {
    return {
      data: {
        year: '',
        make: '',
        model: '',
        vin: '',
      },
      positive,
    }
  },
  created() {
    console.log(this.$route.params.id)
    const { id } = this.$route.params
    if (id !== '0') {
      this.$http.get(`/api/vehicles/${id}`)
        .then(res => {
          this.data = res.data.data
        })
    }
  },
  methods: {
    validationForm() {
      this.$refs.simpleRules.validate().then(() => {
        const data = {
          year: this.data.year,
          make: this.data.make,
          model: this.data.model,
          vin: this.data.vin,
        }
        const { id } = this.$route.params
        if (id === '0') {
          this.$http.post('/api/vehicles', data).then(() => {
            this.$router.replace('/vehicles').then(() => {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'The vehicle was created',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
            })
          })
        } else {
          this.$http.put(`/api/vehicles/${id}`, data).then(() => {
            this.$router.replace('/vehicles').then(() => {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'The vehicle was updated',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
            })
          })
        }
      })
    },
  },
}
</script>
