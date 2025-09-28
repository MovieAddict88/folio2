import React, { useState, useEffect } from 'react';
import './ManageDownloads.css';

const ManageDownloads = () => {
  const [downloads, setDownloads] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [isFormOpen, setIsFormOpen] = useState(false);
  const [currentDownload, setCurrentDownload] = useState(null);

  useEffect(() => {
    fetchDownloads();
  }, []);

  const fetchDownloads = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/downloads.php');
      const data = await response.json();
      if (data.error) throw new Error(data.error);
      setDownloads(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleAddClick = () => {
    setCurrentDownload({ file_name: '', description: '', file_path: '', is_password_protected: false });
    setIsFormOpen(true);
  };

  const handleEditClick = (download) => {
    setCurrentDownload(download);
    setIsFormOpen(true);
  };

  const handleDeleteClick = async (downloadId) => {
    if (window.confirm('Are you sure you want to delete this file record?')) {
      try {
        const response = await fetch('/api/admin/downloads.php', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: downloadId }),
        });
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        fetchDownloads();
      } catch (err) {
        alert('Error deleting download: ' + err.message);
      }
    }
  };

  const handleFormSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const downloadData = {
      file_name: formData.get('file_name'),
      description: formData.get('description'),
      file_path: formData.get('file_path'),
      is_password_protected: formData.get('is_password_protected') === 'on',
      password: formData.get('password'),
    };

    if (currentDownload?.id) {
      downloadData.id = currentDownload.id;
    }

    try {
      const response = await fetch('/api/admin/downloads.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(downloadData),
      });
      const data = await response.json();
      if (!data.success) throw new Error(data.message);
      setIsFormOpen(false);
      fetchDownloads();
    } catch (err) {
      alert('Error saving download: ' + err.message);
    }
  };

  if (loading) return <p>Loading downloads...</p>;
  if (error) return <p className="error-message">Error: {error}</p>;

  return (
    <div className="manage-downloads">
      <div className="manage-header">
        <h2>Manage Downloads</h2>
        <button onClick={handleAddClick} className="add-button">Add New Download</button>
      </div>

      {isFormOpen && (
        <div className="form-modal">
          <form onSubmit={handleFormSubmit}>
            <h3>{currentDownload?.id ? 'Edit Download' : 'Add New Download'}</h3>
            <input name="file_name" type="text" placeholder="File Name" defaultValue={currentDownload?.file_name} required />
            <textarea name="description" placeholder="Description" defaultValue={currentDownload?.description}></textarea>
            <input name="file_path" type="text" placeholder="File Path (e.g., /path/to/file.pdf)" defaultValue={currentDownload?.file_path} required />
            <label>
              <input name="is_password_protected" type="checkbox" defaultChecked={!!currentDownload?.is_password_protected} />
              Password Protected
            </label>
            <input name="password" type="password" placeholder="New Password (only if changing)" />
            <div className="form-buttons">
              <button type="button" onClick={() => setIsFormOpen(false)}>Cancel</button>
              <button type="submit">Save</button>
            </div>
          </form>
        </div>
      )}

      <table className="downloads-table">
        <thead>
          <tr>
            <th>File Name</th>
            <th>Description</th>
            <th>Password Protected</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {downloads.map(download => (
            <tr key={download.file_id}>
              <td>{download.file_name}</td>
              <td>{download.description}</td>
              <td>{download.is_password_protected ? 'Yes' : 'No'}</td>
              <td className="actions">
                <button onClick={() => handleEditClick(download)}>Edit</button>
                <button onClick={() => handleDeleteClick(download.file_id)} className="delete">Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ManageDownloads;