class AdminNotifyEditor {
  constructor() {
    this.changed = false;
    $('.sn-edit-button').on('click', this.editButtonClicked.bind(this));
    $('.layout-content').on('change', this.update.bind(this));
    $('.notify-editor .wcn-edit-control').on('change', this.update.bind(this) );
    $('.edit-control-container input').on('change', this.textBoxCanged).bind(this);
    $('.sn-drag-item').on('dragstart', function(evt) {
      evt.originalEvent.dataTransfer.setData('text', ' ' + evt.target.id + ' ');
    });
    $('.edit-control-container input').on('drop', this.elementDropped.bind(this));

    this.loadNewStyle();
  }

  elementDropped() {
    setTimeout(this.update.bind(this), 100);
  }

  get CurrentStlye() {
    return $('#sn_style_content').val();
  }

  openStyleEditor() {
    const url = notify_editor_vars.editor_url + '&style=' + this.CurrentStlye;
    window.open(url, '_self');
  }

  textBoxCanged() {
    this.changed = true;
  }

  editButtonClicked() {
    if (this.changed) {
      $('#saveModal').modal('show');
      return;
    }
    this.openStyleEditor();
  }

  update() {
    this.showPreviewPopup(this.CurrentStlye);
  }

  showPreviewPopup(style) {
    const id = 'sn_admin_sample';
    const keyVals = {ProductName: 'T-Shirt', GivenName: 'Valérie'};
    $(`#${id}`).remove();
    const notify = new SnNotify(id, keyVals, $('#sn_title_content').val(), $('#sn_message_content').val(), '#', '', style);
    notify.setElement('.notify-preview .panel-body');
    notify.setEnterAnimation($('#sn_enteranimation').val());
    notify.setPosition('static');
    notify.show();
  }

  styleLoaded(styleContent) {
    $('#wcn_style_sheet').html(styleContent);
    this.showPreviewPopup(this.CurrentStlye);
  }

  loadNewStyle() {
    changed = true;
    const data = {
      'action': 'wcn_get_style',
      'style_id': this.CurrentStlye,
    };
    sendAjaxSync(data).then(this.styleLoaded.bind(this));
  }
}

jQuery(document).ready(function($) {
  const editor = new AdminNotifyEditor();
});
