let id = 1;
function add_field() {
  let field;
  if(id <= 1){
    field = document.getElementById("edit-category")
  }else {
    field = document.getElementById("edit-category-" + id);
  }
  if(field.value !== ""){
    let clone = field.cloneNode(true);
    const removeButton = document.getElementsByClassName('remove-category')[0];
    removeButton["disabled"] = id <= 0;
    id++;
    clone['id'] = ('edit-category-' + id);
    clone['value'] = "";
    field.after(clone);
    field.disabled = true;
    stop();
  }

}

function remove_field(){
  const field = document.getElementById("edit-category-" + id);
  field.remove();
  id--;
  if(id <= 1){
    const prevField = document.getElementById("edit-category");
    prevField.disabled = false;
  }else {
    const prevField = document.getElementById("edit-category-" + id);
    prevField.disabled = false;
  }
  const removeButton = document.getElementsByClassName('remove-category')[0];
  removeButton["disabled"] = id <= 1;
}
