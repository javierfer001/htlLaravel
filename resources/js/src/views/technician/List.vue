<template>
    <b-card-code>
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
        <!-- Column: Action -->
        <span v-if="props.column.field === 'action'">
          <b-button variant="outline-primary">
            <feather-icon
              icon="Edit2Icon"
            />
          </b-button>
          <b-button variant="outline-danger">
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
  BButton, BPagination, BFormSelect,
} from 'bootstrap-vue'

import { VueGoodTable } from 'vue-good-table'

import store from '@/store/index'

export default {
  components: {
    BCardCode,
    VueGoodTable,
    BButton,
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
          label: 'FIRST NAME',
          field: 'first_name',
        },
        {
          label: 'LAST NAME',
          field: 'last_name',
        },
        {
          label: 'TRUCK NUMBER',
          field: 'truck_number',
        },
        {
          label: 'ACTION',
          field: 'action',
        },
      ],
      rows: [],
    }
  },
  computed: {
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
    this.$http.get('/api/technicians')
      .then(res => {
        this.rows = res.data.data
      })
  },
}
</script>
