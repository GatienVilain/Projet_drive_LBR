function openPopup()
{
	document.getElementById("popup-addright").style.display = "block";

}

function closePopup()
{
	document.getElementById("popup-addright").style.display = "none";
}

function showTagOptions()
{
	var category_selector = document.getElementById('category-selector');
	var option_selected = ".tag-option." + category_selector.options[category_selector.selectedIndex].value;

	document.querySelectorAll(".tag-option").forEach(a=>a.style.display = "none");
	document.querySelectorAll(option_selected).forEach(a=>a.style.display = "block");
}