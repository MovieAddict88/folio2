import React, { useState, useEffect, useRef } from 'react';
import ReCAPTCHA from 'react-google-recaptcha';
import './Contact.css';

// IMPORTANT: You must replace this with your actual Google reCAPTCHA site key.
const RECAPTCHA_SITE_KEY = "ENTER_YOUR_RECAPTCHA_SITE_KEY_HERE";

const Contact = () => {
  const [formData, setFormData] = useState({ name: '', email: '', message: '' });
  const [status, setStatus] = useState('');
  const [socialLinks, setSocialLinks] = useState({});
  const recaptchaRef = useRef();

  useEffect(() => {
    fetch('/api/about.php')
      .then(res => res.json())
      .then(data => {
        if (data && !data.error) {
          setSocialLinks({
            address: data.address,
            email: data.email,
            facebook: data.facebook_url,
            tiktok: data.tiktok_url,
            youtube: data.youtube_url,
            instagram: data.instagram_url,
          });
        }
      })
      .catch(console.error);
  }, []);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const token = recaptchaRef.current.getValue();
    if (!token && !RECAPTCHA_SITE_KEY.includes("ENTER_YOUR")) {
      setStatus('Please complete the CAPTCHA.');
      return;
    }
    setStatus('Sending...');

    fetch('/api/contact.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ...formData, 'g-recaptcha-response': token }),
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          setStatus(data.message);
          setFormData({ name: '', email: '', message: '' });
          if(recaptchaRef.current) recaptchaRef.current.reset();
        } else {
          throw new Error(data.message || 'An error occurred.');
        }
      })
      .catch(error => {
        setStatus(error.message);
      });
  };

  return (
    <div className="contact-section">
      <h2>Contact Me</h2>
      <div className="contact-container">
        <div className="contact-info">
          <h3>Get in Touch</h3>
          {socialLinks.address && <p>📍 {socialLinks.address}</p>}
          {socialLinks.email && <p>✉️ <a href={`mailto:${socialLinks.email}`}>{socialLinks.email}</a></p>}
          <div className="social-media-links">
            {socialLinks.facebook && <a href={socialLinks.facebook} target="_blank" rel="noopener noreferrer">Facebook</a>}
            {socialLinks.tiktok && <a href={socialLinks.tiktok} target="_blank" rel="noopener noreferrer">TikTok</a>}
            {socialLinks.youtube && <a href={socialLinks.youtube} target="_blank" rel="noopener noreferrer">YouTube</a>}
            {socialLinks.instagram && <a href={socialLinks.instagram} target="_blank" rel="noopener noreferrer">Instagram</a>}
          </div>
        </div>
        <div className="contact-form-container">
          <form onSubmit={handleSubmit} className="contact-form">
            <input type="text" name="name" placeholder="Your Name" value={formData.name} onChange={handleChange} required />
            <input type="email" name="email" placeholder="Your Email" value={formData.email} onChange={handleChange} required />
            <textarea name="message" placeholder="Your Message" value={formData.message} onChange={handleChange} required></textarea>
            {RECAPTCHA_SITE_KEY.includes("ENTER_YOUR") ? (
              <p className="error-message">reCAPTCHA is not configured. Please add your site key in config.php and Contact.jsx.</p>
            ) : (
              <ReCAPTCHA
                ref={recaptchaRef}
                sitekey={RECAPTCHA_SITE_KEY}
              />
            )}
            <button type="submit">Send Message</button>
          </form>
          {status && <p className="form-status">{status}</p>}
        </div>
      </div>
    </div>
  );
};

export default Contact;