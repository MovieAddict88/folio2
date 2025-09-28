import React, { useState, useEffect } from 'react';
import './Downloads.css';

const Downloads = () => {
  const [downloads, setDownloads] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [passwordPrompt, setPasswordPrompt] = useState({ isOpen: false, fileId: null });
  const [password, setPassword] = useState('');
  const [status, setStatus] = useState({});

  useEffect(() => {
    fetch('/api/downloads.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) throw new Error(data.error);
        setDownloads(data);
        setLoading(false);
      })
      .catch(error => {
        setError(error.message);
        setLoading(false);
      });
  }, []);

  const handleDownloadClick = (file) => {
    if (file.is_password_protected) {
      setPasswordPrompt({ isOpen: true, fileId: file.file_id });
    } else {
      generateLinkAndDownload(file.file_id);
    }
  };

  const handlePasswordSubmit = (e) => {
    e.preventDefault();
    generateLinkAndDownload(passwordPrompt.fileId, password);
  };

  const generateLinkAndDownload = (fileId, pass) => {
    setStatus({ ...status, [fileId]: 'Generating link...' });
    fetch('/api/generate-download-link.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ file_id: fileId, password: pass }),
    })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          throw new Error(data.error);
        }
        if (data.success && data.token) {
          setStatus({ ...status, [fileId]: 'Downloading...' });
          window.location.href = `/download.php?token=${data.token}`;
          setPasswordPrompt({ isOpen: false, fileId: null });
          setPassword('');
        }
      })
      .catch(err => {
        setStatus({ ...status, [fileId]: err.message });
      });
  };

  if (loading) return <div className="downloads-section"><h2>Downloads</h2><p>Loading...</p></div>;
  if (error) return <div className="downloads-section"><h2>Downloads</h2><p>Error: {error}</p></div>;

  return (
    <div className="downloads-section">
      <h2>Downloads</h2>
      <div className="downloads-list">
        {downloads.map((file) => (
          <div key={file.file_id} className="download-item">
            <div className="file-info">
              <h3>{file.file_name} {file.is_password_protected ? '🔒' : ''}</h3>
              <p>{file.description}</p>
              {status[file.file_id] && <p className="status-message">{status[file.file_id]}</p>}
            </div>
            <button onClick={() => handleDownloadClick(file)} className="download-button">Download</button>
          </div>
        ))}
      </div>
      {passwordPrompt.isOpen && (
        <div className="password-modal-overlay">
          <div className="password-modal">
            <h3>Enter Password</h3>
            <p>This file is password protected.</p>
            <form onSubmit={handlePasswordSubmit}>
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Password"
                required
              />
              <div className="modal-buttons">
                <button type="button" onClick={() => setPasswordPrompt({ isOpen: false, fileId: null })}>Cancel</button>
                <button type="submit">Submit</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Downloads;