class AdminNotifyEditor {
  constructor() {
    this.changed = false;
    $('.sn-edit-button').on('click', this.editButtonClicked.bind(this));
    $('.layout-content').on('click', this.loadNewStyle.bind(this));
    $('.edit-control-container input').on('change', this.textBoxCanged).bind(this);
    $('.sn-drag-item').on('dragstart', function(evt) {
      evt.originalEvent.dataTransfer.setData('text', ' ' + evt.target.id + ' ');
    });
    $('.edit-control-container input').on('drop', this.elementDropped.bind(this));

    this.loadNewStyle();
  }

  elementDropped() {
    setTimeout(() => {ShowPreviewPopup($('#sn_style_content').val());}, 100);
  }

  get CurrentStlye() {
    // $("#sn_style_content").children(":selected").attr("id")
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

  styleLoaded(styleContent) {
    $('#wcn_style_sheet').html(styleContent);
    ShowPreviewPopup(this.CurrentStlye);
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
