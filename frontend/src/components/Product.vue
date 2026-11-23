Product.vue
<template>
  <div>
    <Navbar />
    <Footer />
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="items"
        class="elevation-2 pa-4"
        :loading="isLoading"
        loading-text="Loading... Please wait"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Manage Product</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="550px">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="secondary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                  @click="formTitle = 'Add Product'"
                >
                  <v-icon left>fas fa-plus</v-icon>
                  Add
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-row>
                        <v-col cols="6">
                          <v-text-field
                            v-model="editedItem.nama_barang"
                            label="Product Name"
                            prepend-icon="mdi-plus-box-outline"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field
                            v-model="editedItem.harga"
                            label="Price"
                            @keypress="onlyNumber" type="text"
                          ></v-text-field>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="6">
                          <v-text-field
                            v-model="editedItem.jumlah_stock_tersedia"
                            label="Quantity"
                            @keypress="onlyNumber" type="text"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field
                            v-model="editedItem.deskripsi"
                            label="Description"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="secondary" text @click="close"> Cancel </v-btn>
                  <v-btn color="secondary" text @click="save"> Save </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="500px">
              <v-card>
                <v-card-title class="text-h5"
                  >Are you sure you want to delete this item?</v-card-title
                >
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="secondary" text @click="closeDelete"
                    >Cancel</v-btn
                  >
                  <v-btn color="secondary" text @click ="deleteItem"
                    >OK</v-btn
                  >
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <div class="align-center">
            <v-menu transition="slide-y-transition" offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon color="secondary" v-bind="attrs" v-on="on">
                  <v-icon>fas fa-ellipsis-vertical</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="deleteItem(item);">
                  <v-icon style="color: red" small class="mr-2"
                    >fa-solid fa-trash</v-icon
                  >
                  <v-list-item-title>Delete</v-list-item-title>
                </v-list-item>
                <v-list-item @click="editItem(item)">
                  <v-icon style="color: orange" small class="mr-2"
                    >fa-solid fa-pen</v-icon
                  >
                  <v-list-item-title>Edit</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </div>
        </template>
      </v-data-table>
    </v-card-text>
    <v-snackbar
           v-model="snackbar1"
           absolute
          top
          color="success"
          outlined
          right
          timeout= 1500
           >
            The Data Successfully Add
          </v-snackbar>
          <v-snackbar
           v-model="snackbar2"
            absolute
          top
          color="orange"
          outlined
          right
          timeout = 1500
           >
            The Data Successfully Edit
          </v-snackbar>
          <v-snackbar
           v-model="snackbar3"
            absolute
          top
          color="error"
          outlined
          right
          timeout = 1500
           >
            The Data Successfully Delete
          </v-snackbar>
  </div>
</template>

<script>
import api from "@/services/api";
import Navbar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
export default {
  components: {
    Navbar,
    Footer,
  },
  data() {
    return {
      dialog: false,
      dialogDelete: false,
      snackbar: false,
      formTitle: "",
      icons: [{ icon: "mdi-delete", text: "delete" }, { icon: "mdi-pencil" }],
      headers: [
        { text: "Product Name", value: "nama_barang" },
        { text: "Price", value: "harga" },
        { text: "Quantity", value: "jumlah_stock_tersedia" },
        { text: "Description", value: "deskripsi" },
        { text: "Actions", value: "actions", sortable: false },
      ],
      isLoading: false,
      snackbar1: false,
      snackbar2: false,
      snackbar3: false,

      items: [],
      editedIndex: -1,
      editedItem: {
        nama_barang: "",
        harga: "",
        jumlah_stock_tersedia: "",
        deskripsi: "",
      },
      defaultItem: {
        nama_barang: "",
        harga: "",
        jumlah_stock_tersedia: "",
        deskripsi: "",
      },
    };
  },

  methods: {
    editItem(item) {
      this.formTitle = "Edit Product";
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },
    async deleteItemConfirm() {
      try {
        await api.delete(`/product/${this.editedItem.id}`);
        this.snackbar3 = true;
        await this.fetchData();
        this.closeDelete();
      } catch (error) {
        console.error("Error deleting item:", error);
      }
    },

    close() {
      this.dialog = false;
      this.clearEditedItem();
    },
    closeDelete() {
      this.dialogDelete = false;
      this.clearEditedItem();
    },
    clearEditedItem() {
      this.editedItem = {};
    },

    onlyNumber ($event) {
   //console.log($event.keyCode); //keyCodes value
   let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
   if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
      $event.preventDefault();
   }
},

    //get data
    async fetchData() {
      try {
        const response = await api.get("/product");
        this.items = response.data.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      }finally{
        this.isLoading = false;
      }
    },

    //post n put data
    async save() {
      try {

        if (this.formTitle === "Add Product") {
          console.log("Sending POST request...");
          console.log(this.editedItem);

          await api.post(
            "/product",
            this.editedItem
          );
          api
            .get("/product")
            .then((response) => {
              this.items = response.data.data;
              this.snackbar1 = true;
              this.dialog = false;
            })
            .catch((error) => {
              console.log(error.response);
            });

        } else {
          console.log("Sending PUT request...");
          await api.put(
            `/product/${this.editedItem.id}`,
            this.editedItem
          );
          api
            .get("/product")
            .then((response) => {
              this.items = response.data.data;
              this.snackbar2 = true;
              this.dialog = false;
            })
            .catch((error) => {
              console.log(error.response);
            });
        }

        console.log("Request successful!");
      } catch (error) {
        console.error("Error saving data:", error.response);
      }
    },
  },
  mounted() {
    this.isLoading = true;
    this.fetchData();
  },
};
</script>

<style>
/* .v-list-item--link:before{
  background-color: #1976D2;
  z-index: 2;
}
.v-list-item__title{
  z-index: 1;
} */
</style>
