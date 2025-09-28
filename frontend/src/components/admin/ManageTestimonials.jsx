import React, { useState, useEffect } from 'react';
import './ManageTestimonials.css';

const ManageTestimonials = () => {
  const [testimonials, setTestimonials] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [isFormOpen, setIsFormOpen] = useState(false);
  const [currentTestimonial, setCurrentTestimonial] = useState(null);

  useEffect(() => {
    fetchTestimonials();
  }, []);

  const fetchTestimonials = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/testimonials.php');
      const data = await response.json();
      if (data.error) throw new Error(data.error);
      setTestimonials(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleAddClick = () => {
    setCurrentTestimonial({ author_name: '', author_position: '', testimonial_text: '', author_image_url: '', video_url: '' });
    setIsFormOpen(true);
  };

  const handleEditClick = (testimonial) => {
    setCurrentTestimonial(testimonial);
    setIsFormOpen(true);
  };

  const handleDeleteClick = async (testimonialId) => {
    if (window.confirm('Are you sure you want to delete this testimonial?')) {
      try {
        const response = await fetch('/api/admin/testimonials.php', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: testimonialId }),
        });
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        fetchTestimonials();
      } catch (err) {
        alert('Error deleting testimonial: ' + err.message);
      }
    }
  };

  const handleFormSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const testimonialData = Object.fromEntries(formData.entries());
    if (currentTestimonial?.id) {
      testimonialData.id = currentTestimonial.id;
    }

    try {
      const response = await fetch('/api/admin/testimonials.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(testimonialData),
      });
      const data = await response.json();
      if (!data.success) throw new Error(data.message);
      setIsFormOpen(false);
      fetchTestimonials();
    } catch (err) {
      alert('Error saving testimonial: ' + err.message);
    }
  };

  if (loading) return <p>Loading testimonials...</p>;
  if (error) return <p className="error-message">Error: {error}</p>;

  return (
    <div className="manage-testimonials">
      <div className="manage-header">
        <h2>Manage Testimonials</h2>
        <button onClick={handleAddClick} className="add-button">Add New Testimonial</button>
      </div>

      {isFormOpen && (
        <div className="form-modal">
          <form onSubmit={handleFormSubmit}>
            <h3>{currentTestimonial?.id ? 'Edit Testimonial' : 'Add New Testimonial'}</h3>
            <input name="author_name" type="text" placeholder="Author Name" defaultValue={currentTestimonial?.author_name} required />
            <input name="author_position" type="text" placeholder="Author Position" defaultValue={currentTestimonial?.author_position} />
            <textarea name="testimonial_text" placeholder="Testimonial Text" defaultValue={currentTestimonial?.testimonial_text}></textarea>
            <input name="author_image_url" type="text" placeholder="Author Image URL" defaultValue={currentTestimonial?.author_image_url} />
            <input name="video_url" type="text" placeholder="Video URL (optional)" defaultValue={currentTestimonial?.video_url} />
            <div className="form-buttons">
              <button type="button" onClick={() => setIsFormOpen(false)}>Cancel</button>
              <button type="submit">Save</button>
            </div>
          </form>
        </div>
      )}

      <table className="testimonials-table">
        <thead>
          <tr>
            <th>Author</th>
            <th>Text</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {testimonials.map(testimonial => (
            <tr key={testimonial.id}>
              <td>{testimonial.author_name}</td>
              <td>{testimonial.testimonial_text ? testimonial.testimonial_text.substring(0, 100) + '...' : 'Video Testimonial'}</td>
              <td className="actions">
                <button onClick={() => handleEditClick(testimonial)}>Edit</button>
                <button onClick={() => handleDeleteClick(testimonial.id)} className="delete">Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ManageTestimonials;