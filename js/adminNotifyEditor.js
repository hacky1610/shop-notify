class AdminNotifyEditor {
  constructor() {
    this.changed = false;
    this.showing = false;
    $('.sn-edit-button').on('click', this.editButtonClicked.bind(this));


    $('#sn_style_content').on('change', this.loadNewStyle.bind(this));
    $('#sn_placement').on('change', this.update.bind(this));
    $('#sn_enteranimation').on('change', this.showEnterAnimation.bind(this));
    $('#sn_exitanimation').on('change', this.showExitAnimation.bind(this));

    $('.notify-editor .wcn-edit-control').on('change', this.update.bind(this) );
    $('.sn_edit_container input').on('change', this.textBoxCanged).bind(this);
    $('.sn-drag-item').on('dragstart', function(evt) {
      evt.originalEvent.dataTransfer.setData('text', ' ' + evt.target.id + ' ');
    });
    $('.sn_edit_container input').on('drop', this.elementDropped.bind(this));

    this.loadNewStyle();
  }

  elementDropped(e) {
    setTimeout(this.update.bind(this), 10);
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
    document.getSelection().empty();

    this.showPreviewPopup(this.CurrentStlye);
  }

  showEnterAnimation() {
    this.showPreviewPopup(this.CurrentStlye, true);
  }

  showExitAnimation() {
    this.showPreviewPopup(this.CurrentStlye, false).then( () => {
      this.notify.close();
      setTimeout(() => {
        this.showPreviewPopup(this.CurrentStlye);
      }, 2000);
    });
  }

  showPreviewPopup(style, showEnterAnimation = false) {
    if (this.showing) {
      return;
    }

    this.showing = true;
    const id = 'sn_admin_sample';
    $(`#${id}`).remove();
    const keyVals = {ProductName: 'T-Shirt', GivenName: 'Valérie', Bought: 'one hour ago', Country: 'Germany'};
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
    const showPromise = this.notify.show();
    showPromise.then( this.displayed.bind(this));

    return showPromise;
  }

  displayed() {
    this.showing = false;
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
