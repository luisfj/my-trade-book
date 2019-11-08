
<b-modal id="modal-reportar-problema" size="lg" title="Reportar Problema"
      header-bg-variant="dark"
      header-text-variant="light"
      body-bg-variant="secondary"
      body-text-variant="light"
      footer-bg-variant="secondary"
      footer-text-variant="light">
    <p class="my-4">Hello from modal!</p>

    <template v-slot:modal-footer="{ cancel, ok }">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button style="width: 80px;" size="sm" variant="danger" @click="cancel()">
            Cancelar
        </b-button>
        <b-button style="width: 80px;" size="sm" variant="success" @click="ok()">
            OK
        </b-button>

      </template>
</b-modal>
