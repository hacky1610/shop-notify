class AdminNotifyEditor {
  constructor() {
    this.changed = false;
    $('.sn-edit-button').on('click', this.editButtonClicked.bind(this));

    
    $('#sn_style_content').on('change', this.loadNewStyle.bind(this));
    $('#sn_placement').on('change', this.update.bind(this));
    $('#sn_enteranimation').on('change', this.update.bind(this));
    $('#sn_exitanimation').on('change', this.showExitAnimation.bind(this));

    $('.notify-editor .wcn-edit-control').on('change', this.update.bind(this) );
    $('.sn_edit_container input').on('change', this.textBoxCanged).bind(this);
    $('.sn-drag-item').on('dragstart', function(evt) {
      evt.originalEvent.dataTransfer.setData('text', ' ' + evt.target.id + ' ');
    });
    $('.sn_edit_container input').on('drop', this.elementDropped.bind(this));

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

  showExitAnimation() {
    this.showPreviewPopup(this.CurrentStlye, false).then( () => {
      this.notify.close();
      setTimeout(() => {this.showPreviewPopup(this.CurrentStlye, false);}, 2000);
    });
  }

  showPreviewPopup(style, showEnterAnimation = true) {
    const id = 'sn_admin_sample';
    const keyVals = {ProductName: 'T-Shirt', GivenName: 'Valérie', Bought: 'one hour ago', Country: 'Germany'};
    $(`#${id}`).remove();
    this.notify = new SnNotify(id, keyVals, $('#sn_title_content').val(), $('#sn_message_content').val(), '#', '', style);
    this.notify.setElement('.preview .panel-body');
    if (showEnterAnimation) {
      this.notify.setEnterAnimation($('#sn_enteranimation').val());
    } else {
      this.notify.setEnterAnimation(null);
    }
    this.notify.setOffset(5);
    this.notify.setPlacement(this.getPlacement($('#sn_placement').val()));
    this.notify.setExitAnimation($('#sn_exitanimation').val());
    this.notify.setPosition('absolute');
    return this.notify.show();
  }

  getPlacement(placementText) {
    return {
      from: placementText.split('-')[0],
      align: placementText.split('-')[1],
    };
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
