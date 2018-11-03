class Runner {
  constructor() {
    this.load(this.loadElements.bind(this));
  }

  loadElements(res) {
    this.controllers = ControllerSerializer.deserialize(res);

    this.renderAll();
  };

  load(callback) {
    const d = {
      'action': 'wcn_get_workflow',
    };
    sendAjaxSync(d).then(callback);
  }

  async renderAll() {
    for (let i = 0; i < this.controllers.length; i++) {
      let controller = this.controllers[i];
      snLogger.Info(`Run element ${controller.Type}`);
      await controller.run();
      snLogger.Info(`Finished element ${controller.Type}`);
    }
  }
}
new Runner();