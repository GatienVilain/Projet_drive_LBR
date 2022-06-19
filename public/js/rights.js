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

	document.querySelectorAll(".tag-option").forEach(a => a.style.display = "none");
	document.querySelectorAll(option_selected).forEach(a => a.style.display = "block");
}

function linkToWritingTag(event)
{
	let reading_checkbox = event.currentTarget;

	if (reading_checkbox.checked)
	{
		let writing_checkbox = document.getElementById("checkbox-e" + reading_checkbox.name.substr(1));
		if (writing_checkbox != null)
		{
			writing_checkbox.checked = true;
		}
	}
}

const checkbox_rights = document.querySelectorAll('.check-right-lecture');

checkbox_rights.forEach(tag => {
	tag.addEventListener('change', linkToWritingTag, false);
});