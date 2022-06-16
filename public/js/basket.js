console.log("test");
const files = document.querySelectorAll('.popup');
let timer;
files.forEach(file => file.addEventListener('click', event => {
  closeAllPopup();
  if(event.button == 0) {//clic gauche
	  if (event.detail === 1) {//simple clic
		timer = setTimeout(() => {
		  idElement = file.id + '-popup-detail';
		  if(document.getElementById(idElement).style.display != "block")
		  {
			document.getElementById(idElement).style.display = "block";  
		  }
		}, 200);
	  }
	}
}));

files.forEach(file => file.addEventListener('dblclick', event => {
  clearTimeout(timer);
	  //double clic gauche
	  if(file.tagName == 'IMG'){
		var newpath = file.getAttribute('src').substr(0,16) + file.getAttribute('src').substr(23);
		console.log(newpath);
	    openPopupModal(file.tagName,newpath);
	  }
	  else if(file.tagName == 'VIDEO'){
		openPopupModal(file.tagName,file.children[0].getAttribute('src'));
	  }
}));

files.forEach(file => file.addEventListener('contextmenu', event => {
  //clic droit
  closeAllPopup();
  idElement = file.id + '-popup-options';
  if(document.getElementById(idElement).style.display != "block")
  {
	 document.getElementById(idElement).style.display = "block";
  }
}));

//popup modal functions
  function openPopupModal(type,path){
	  var popup = document.getElementById("show_image_popup");
	  if (popup.style.display = "none"){
		popup.style.display = "flex";
	  }
	  
	  if(type == 'IMG'){
		  var image = document.getElementById("image-show-area");
		  image.children[0].src = path;
		  if (image.style.display = "none"){
			image.style.display = "flex";
		  }
	  }
	  else if(type == 'VIDEO'){
		  var video = document.getElementById("video-show-area");
		  video.children[0].src = path;
		  if (video.style.display = "none"){
			video.style.display = "flex";
		  }
	  }
  }

  function hidePopupModal(){
	  document.getElementById("show_image_popup").style.display = "none";
	  document.getElementById("image-show-area").style.display = "none";
	  document.getElementById("image-show-area").children[0].src = "";
	  document.getElementById("video-show-area").style.display = "none";
	  document.getElementById("video-show-area").children[0].src = "";
  }


  function openFilterMenu(){
	closeAllPopup();
	document.getElementById("popup-filter-menu").style.visibility = "visible";
	
  }

  function closeFilterMenu(){
	document.getElementById("popup-filter-menu").style.display = "none";
	
  }

  function openPopupUpload() {
	document.getElementById("popup-upload").style.display = "block";

  }

  function closePopupUpload() {
	  document.getElementById("popup-upload").style.display = "none";
  }

  function closeAllPopup(){

	let popups_options = document.getElementsByClassName('popup-options');
	for(valeur of popups_options)
	  {
		valeur.style.display = "none";
	  }


	let popups_detail = document.getElementsByClassName('popup-detail');
	for(valeur of popups_detail)
	  {
		valeur.style.display = "none";
	  }
  }

  function buttonClosePopupUpload() {
	document.getElementById("popup-upload").style.display = "none";
		window.location.reload(); 
  }

  function closePopupDetail(idElement) {
	idElement = idElement + '-popup-detail';
	document.getElementById(idElement).style.display = "none";
  }

  function closePopupOptions(idElement) {
	idElement = idElement + '-popup-options';
	document.getElementById(idElement).style.display = "none";
  }

  function AntiClickDroitImg()
 {
  var imgs = document.getElementsByTagName('img');
  for(var i=0; i<imgs.length; i++)
   imgs[i].oncontextmenu = NeRienFaire;
 }

function deleteFile(idFichier)
{

  //var file_path = "storage/pictures/58.png";
  $.ajax({
		url: 'index.php',
		data: {'idFile' : idFichier,'action' : "deleteFile"},
		dataType: 'json', 
		success: function (response) {
		  if( response.status === true ) {
			  alert('File Deleted!');
			  window.location.reload();
		  }
		  else alert('Something Went Wrong!');
		}
	  });
  }
