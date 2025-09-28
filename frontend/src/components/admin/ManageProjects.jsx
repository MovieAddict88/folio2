import React, { useState, useEffect } from 'react';
import './ManageProjects.css';

const ManageProjects = () => {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [isFormOpen, setIsFormOpen] = useState(false);
  const [currentProject, setCurrentProject] = useState(null);

  useEffect(() => {
    fetchProjects();
  }, []);

  const fetchProjects = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/projects.php');
      const data = await response.json();
      if (data.error) throw new Error(data.error);
      setProjects(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleAddClick = () => {
    setCurrentProject({ title: '', description: '', media_url: '', external_links: '[]', category_tags: '[]' });
    setIsFormOpen(true);
  };

  const handleEditClick = (project) => {
    setCurrentProject(project);
    setIsFormOpen(true);
  };

  const handleDeleteClick = async (projectId) => {
    if (window.confirm('Are you sure you want to delete this project?')) {
      try {
        const response = await fetch('/api/admin/projects.php', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: projectId }),
        });
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        fetchProjects(); // Refresh list
      } catch (err) {
        alert('Error deleting project: ' + err.message);
      }
    }
  };

  const handleFormSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const projectData = Object.fromEntries(formData.entries());
    if (currentProject?.id) {
        projectData.id = currentProject.id;
    }

    try {
      const response = await fetch('/api/admin/projects.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(projectData),
      });
      const data = await response.json();
      if (!data.success) throw new Error(data.message);
      setIsFormOpen(false);
      fetchProjects(); // Refresh list
    } catch (err) {
      alert('Error saving project: ' + err.message);
    }
  };

  if (loading) return <p>Loading projects...</p>;
  if (error) return <p className="error-message">Error: {error}</p>;

  return (
    <div className="manage-projects">
      <div className="manage-header">
        <h2>Manage Projects</h2>
        <button onClick={handleAddClick} className="add-button">Add New Project</button>
      </div>

      {isFormOpen && (
        <div className="form-modal">
          <form onSubmit={handleFormSubmit}>
            <h3>{currentProject?.id ? 'Edit Project' : 'Add New Project'}</h3>
            <input name="title" type="text" placeholder="Title" defaultValue={currentProject?.title} required />
            <textarea name="description" placeholder="Description" defaultValue={currentProject?.description} required></textarea>
            <input name="media_url" type="text" placeholder="Media URL" defaultValue={currentProject?.media_url} required />
            <input name="external_links" type="text" placeholder='e.g., [{"label":"Live Demo","url":"..."}]' defaultValue={currentProject?.external_links} />
            <input name="category_tags" type="text" placeholder='e.g., ["React","PHP"]' defaultValue={currentProject?.category_tags} />
            <div className="form-buttons">
              <button type="button" onClick={() => setIsFormOpen(false)}>Cancel</button>
              <button type="submit">Save</button>
            </div>
          </form>
        </div>
      )}

      <table className="projects-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {projects.map(project => (
            <tr key={project.id}>
              <td>{project.title}</td>
              <td>{project.description ? project.description.substring(0, 100) + '...' : ''}</td>
              <td className="actions">
                <button onClick={() => handleEditClick(project)}>Edit</button>
                <button onClick={() => handleDeleteClick(project.id)} className="delete">Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ManageProjects;