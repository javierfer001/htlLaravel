<template>
  <b-card-code>
    <b-button
      class="mb-1"
      variant="outline-success"
      href="/orders/0"
    >
      <feather-icon
        icon="PlusIcon"
      />
    </b-button>
    <!-- table -->
    <vue-good-table
      style-class="vgt-table bordered"
      :columns="columns"
      :rows="rows"
      :rtl="direction"
      :pagination-options="{
        enabled: true,
        perPage:pageLength
      }"
    >
      <template
        slot="table-row"
        slot-scope="props"
      >
        <span v-if="props.column.field == 'vehicle'">
          {{ props.row.vehicle.year }}<span style="font-weight: bold;"> {{ props.row.vehicle.make }} {{ props.row.vehicle.model }}</span>
        </span>
        <span v-else-if="props.column.field == 'status'">
          <b-badge :variant="statusVariant(props.row.status)">
            {{ props.row.status }}
          </b-badge>
        </span>
        <span v-else-if="props.column.field == 'technician'">
          {{ props.row.technician.first_name }} {{ props.row.technician.last_name }}
        </span>
        <!-- Column: Action -->
        <span v-else-if="props.column.field === 'action'" style="min-width: 200px">
          <b-button
            :href="'/orders/' + props.row.id"
            variant="outline-primary"
          >
            <feather-icon
              icon="Edit2Icon"
            />
          </b-button>
          <b-button
            variant="outline-danger"
            @click="edit(props.row.id)"
          >
            <feather-icon
              icon="TrashIcon"
            />
          </b-button>
        </span>
        <!-- Column: Common -->
        <span v-else>
          {{ props.formattedRow[props.column.field] }}
        </span>
      </template>

      <!-- pagination -->
      <template
        slot="pagination-bottom"
        slot-scope="props"
      >
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-center mb-0 mt-1">
            <span class="text-nowrap ">
              Showing 1 to
            </span>
            <b-form-select
              v-model="pageLength"
              :options="['10','15','20']"
              class="mx-1"
              @input="(value)=>props.perPageChanged({currentPerPage:value})"
            />
            <span class="text-nowrap"> of {{ props.total }} entries </span>
          </div>
          <div>
            <b-pagination
              :value="1"
              :total-rows="props.total"
              :per-page="pageLength"
              first-number
              last-number
              align="right"
              prev-class="prev-item"
              next-class="next-item"
              class="mt-1 mb-0"
              @input="(value)=>props.pageChanged({currentPage:value})"
            >
              <template #prev-text>
                <feather-icon
                  icon="ChevronLeftIcon"
                  size="18"
                />
              </template>
              <template #next-text>
                <feather-icon
                  icon="ChevronRightIcon"
                  size="18"
                />
              </template>
            </b-pagination>
          </div>
        </div>
      </template>
    </vue-good-table>
  </b-card-code>
</template>
<style scoped>
.vgt-table {
    font-size: 12px !important;
}
</style>
<script>
import BCardCode from '@core/components/b-card-code/BCardCode.vue'

import 'vue-good-table/dist/vue-good-table.css'

import {
  BButton, BBadge, BPagination, BFormSelect,
} from 'bootstrap-vue'

import { VueGoodTable } from 'vue-good-table'

import store from '@/store/index'
import ToastificationContent from '@core/components/toastification/ToastificationContent'

export default {
  components: {
    BCardCode,
    VueGoodTable,
    BButton,
    BBadge,
    BPagination,
    BFormSelect,
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      columns: [
        {
          label: 'ID',
          field: 'id',
        },
        {
          label: 'VEHICLE',
          field: 'vehicle',
        },
        {
          label: 'KEY ID',
          field: 'key.name',
        },
        {
          label: 'TECHNICIAN',
          field: 'technician',
        },
        {
          label: 'NOTE',
          field: 'note',
        },
        {
          label: 'STATUS',
          field: 'status',
        },
        {
          label: 'ACTION',
          field: 'action',
          sortable: false,
          width: '150px',
        },
      ],
      rows: [],
    }
  },
  computed: {
    statusVariant() {
      const statusColor = {
        approved: 'light-success',
        declined: 'light-danger',
        pending: 'light-warning',
      }
      return status => statusColor[status]
    },
    direction() {
      if (store.state.appConfig.isRTL) {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.dir = true
        return this.dir
      }
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.dir = false
      return this.dir
    },
  },
  created() {
    this.$http.get('/api/orders')
      .then(res => {
        this.rows = res.data.data
      })
  },
  methods: {
    edit(id) {
      // eslint-disable-next-line no-restricted-globals
      if (confirm('Are you sure that you want to delete de record')) {
        this.$http.delete(`/api/orders/${id}`)
          .then(() => {
            this.$http.get('/api/orders')
              .then(res => {
                this.rows = res.data.data
              })
          }).catch(() => {
            this.$toast({
              component: ToastificationContent,
              props: {
                title: 'You can not delete the order, other records are using it',
                icon: 'EditIcon',
                variant: 'danger',
              },
            })
          })
      }
    },
  },
}
</script>
