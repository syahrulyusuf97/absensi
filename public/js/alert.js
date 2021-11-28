function deleteData(url) {
	showLoading();
	axios.delete(url)
	.then(function (response) {
	  	hideLoading();
	  	if (response.data.success) {
	  		refreshTable();
	  		alertSuccess('Berhasil', response.data.message);
	  	} else {
	  		alertWarning('Gagal', response.data.message);
	  	}
	  })
	  .catch(function (error) {
	  	hideLoading();
	    alertError('Error', error);
	  });
}

function updateData(url) {
	showLoading();
	axios.put(url)
	.then(function (response) {
	  	hideLoading();
	  	if (response.data.success) {
	  		refreshTable();
	  		alertSuccess('Berhasil', response.data.message);
	  	} else {
	  		alertWarning('Gagal', response.data.message);
	  	}
	  })
	  .catch(function (error) {
	  	hideLoading();
	    alertError('Error', error);
	  });
}

function alertSuccess(title, text) {
	Swal.fire({
	  type: 'success',
	  title: title,
	  text: text,
	  timer: 2000
	})
}

function alertError(title, text) {
	Swal.fire({
	  type: 'error',
	  title: title,
	  text: text,
	  timer: 2000
	})
}

function alertWarning(title, text) {
	Swal.fire({
	  type: 'warning',
	  title: title,
	  text: text,
	  timer: 2000
	})
}

function alertConfirm(title, text, url) {
	Swal.fire({
		type: 'question',
	  	title: title,
	  	text: text,
	  	showCancelButton: true,
	  	confirmButtonText: 'Ya',
	  	cancelButtonText: 'Batal',
	}).then((result) => {
	  /* Read more about isConfirmed */
	  if (result.value) {
	    deleteData(url);
	  }
	})
}

function alertConfirmUpdate(title, text, url) {
	Swal.fire({
		type: 'question',
	  	title: title,
	  	text: text,
	  	showCancelButton: true,
	  	confirmButtonText: 'Ya',
	  	cancelButtonText: 'Batal',
	}).then((result) => {
	  /* Read more about isConfirmed */
	  if (result.value) {
	    updateData(url);
	  }
	})
}