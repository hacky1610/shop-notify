class Runner {
  constructor() {
    this.load(this.loadElements.bind(this));

    // const s = new WfeSleepController();
    // s.run().then(() => {
    //   alert();
    // });
    this.run();
  }

  loadElements(res) {
    this.controllers = ControllerSerializer.deserialize(res);

    this.renderAll();
  };

  load(callback) {
    const d = {
      'action': 'wcn_get_workflow',
    };
    SendAjaxSync(d).then(callback);
  }
  async runElement(element) {
    await element.run()
    console.log("Foo");
  }

  async renderAll() {
    this.controllers.forEach(this.runElement);
  }
}

new Runner();