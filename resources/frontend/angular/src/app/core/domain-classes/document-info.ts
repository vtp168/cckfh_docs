import { DocumentRolePermission } from './document-role-permission';
import { DocumentUserPermission } from './document-user-permission';
import { DocumentMetaData } from './documentMetaData';
import { DocumentVersion } from './documentVersion';

export interface DocumentInfo {
  id?: string;
  name?: string;
  url?: string;
  letter_no?: string;
  letter_date?: Date;
  number_in?: string;
  doc_from?: string;
  dateline?: Date;
  description?: string;
  createdDate?: Date;
  createdBy?: string;
  categoryId?: string;
  categoryName?: string;
  documentSource?: string;
  extension?: string;
  isVersion?: boolean;
  viewerType?: string;
  expiredDate?: Date;
  isAllowDownload?: boolean;
  documentVersions?: DocumentVersion[];
  documentMetaDatas?: DocumentMetaData[];
  documentRolePermissions?: DocumentRolePermission[];
  documentUserPermissions?: DocumentUserPermission[];
  fileData?: any;
  location?: string;
  deletedBy?: string;
}
