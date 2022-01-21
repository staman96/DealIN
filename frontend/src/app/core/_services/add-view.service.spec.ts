import { TestBed } from '@angular/core/testing';

import { AddViewService } from './add-view.service';

describe('AddViewService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: AddViewService = TestBed.get(AddViewService);
    expect(service).toBeTruthy();
  });
});
